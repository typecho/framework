<?php

namespace TE\Mvc\Action;

use TE\Mvc\Action\Interceptor\InterceptorManagerInterface as InterceptorManager;

/**
 * ActionEvent  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class ActionEvent
{
    /**
     * _action  
     * 
     * @var mixed
     * @access private
     */
    private $_action;

    /**
     * _result  
     * 
     * @var mixed
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
     * @var mixed
     * @access private
     */
    private $_manager;

    /**
     * __construct  
     * 
     * @param AbstractAction $action 
     * @access public
     * @return void
     */
    public function __construct(AbstractAction $action, InterceptorManager $manager)
    {
        $this->_action = $action;
        $this->_manager = $manager;
    }

    /**
     * getAction  
     * 
     * @access public
     * @return void
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * invoke
     * 
     * @access public
     * @return void
     */
    public function invoke()
    {
        $interceptor = $this->_manager->fetch();

        if (empty($interceptor)) {
            foreach ($this->_data as $key => $val) {
                if (isset($this->_action->{$key})) {
                    $this->_action->{$key} = $val;
                }
            }

            $result = $this->_action->execute();

            if (NULL === $result) {
                $result = 'empty';
            }

            $viewName = is_array($result) ? array_shift($result) : $result;
            $params = is_array($result) ? $result : array();
            $this->_result = new ActionResult($viewName, $params);

            $data = get_object_vars($this->_action);
            $this->_data = array_merge($this->_data, $data);
        } else {
            $interceptor->intercept($this);
        }
    }
 
    /**
     * getResult  
     * 
     * @access public
     * @return void
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
     * @return void
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
     * @return void
     */
    public function getData($name = NULL)
    {
        if (empty($name)) {
            return $this->_data;
        } else {
            return isset($this->_data[$name]) ? $this->_data[$name] : NULL;
        }
    }
}

