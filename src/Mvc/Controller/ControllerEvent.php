<?php

namespace TE\Mvc\Controller;

use TE\Mvc\Controller\Interceptor\InterceptorManager;
use TE\Settings;

/**
 * ControllerEvent
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class ControllerEvent
{
    /**
     * _controller
     * 
     * @var AbstractController
     * @access private
     */
    private $_controller;

    /**
     * _result  
     * 
     * @var \TE\Mvc\Result\AbstractResult
     * @access private
     */
    private $_result;

    /**
     * _data  
     * 
     * @var array
     * @access private
     */
    private $_data = array();

    /**
     * _manager  
     * 
     * @var InterceptorManager
     * @access private
     */
    private $_manager;

    /**
     * @param AbstractController     $controller
     * @param InterceptorManager $manager
     */
    public function __construct(AbstractController $controller, InterceptorManager $manager)
    {
        $this->_controller = $controller;
        $this->_manager = $manager;
    }

    /**
     * @param $method
     * @return mixed
     */
    private function invokeMethod($method)
    {
        $controllerRef = new \ReflectionClass($this->_controller);
        $methodRef = $controllerRef->getMethod($method);
        $params = $methodRef->getParameters();
        $args = array();
        $request = $this->_controller->getRequest();

        foreach ($params as $param) {
            $name = $param->getName();
            $class = $param->getClass();

            if ($param->isArray()) {
                $args[] = $request->getArray($name,
                    $param->isDefaultValueAvailable() ? $param->getDefaultValue() : array());
            } else if (!empty($class) && $class->isSubclassOf('\TE\Mvc\Form\AbstractForm')) {
                $form = $class->newInstance($request);

                if (!$form->isValid()) {
                    return $this->_controller->formAssert($method, $form);
                }

                $args[] = $form;
            } else {
                $default = $param->isDefaultValueAvailable()
                    ? $param->getDefaultValue() : NULL;
                $value = $request->get($name, $default);
                $args[] = is_array($value) ? $default : $value;
            }
        }

        return call_user_func_array(array($this->_controller, $method), $args);
    }

    /**
     * getController
     * 
     * @access public
     * @return AbstractController
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * invoke
     * 
     * @access public
     * @param string $method
     * @return void
     */
    public function invoke($method)
    {
        $interceptor = $this->_manager->fetch();

        if (empty($interceptor)) {
            foreach ($this->_data as $key => $val) {
                if (empty($this->_controller->{$key})) {
                    $this->_controller->{$key} = $val;
                }
            }

            $result = $this->invokeMethod($method);

            if (NULL === $result) {
                $result = 'blank';
            }

            $resultName = is_array($result) ? array_shift($result) : $result;
            $params = is_array($result) ? $result : array();

            $this->setResult($resultName, $params);
            $data = get_object_vars($this->_controller);
            $this->_data = array_merge($this->_data, $data);
        } else {
            $interceptor->intercept($this);
        }
    }

    /**
     * set result
     *
     * @param string $resultName
     * @param array $params
     */
    public function setResult($resultName, $params)
    {
        $resultClass = Settings::getResultClass($resultName);

        $this->_result = new $resultClass;
        $this->_result->setEvent($this);
        $this->_result->setParams($params);

        Settings::setResult($resultName, $this->_result);
        $this->_result->init();
    }
 
    /**
     * getResult  
     * 
     * @access public
     * @return \TE\Mvc\Result\AbstractResult
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * setData
     * 
     * @param mixed $name 
     * @param mixed $value 
     * @access public
     * @return ControllerEvent
     */
    public function setData($name, $value)
    {
        $this->_data[$name] = $value;
        return $this;
    }

    /**
     * getData
     * 
     * @param mixed $name 
     * @access public
     * @return mixed
     */
    public function getData($name = NULL)
    {
        return empty($name) ? $this->_data : 
            (isset($this->_data[$name]) ? $this->_data[$name] : NULL);
    }
}
