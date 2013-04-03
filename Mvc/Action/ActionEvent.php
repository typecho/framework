<?php

namespace TE\Mvc\Action;

use TE\Mvc\Action\Interceptor\InterceptorManager;

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
     * @var AbstractAction
     * @access private
     */
    private $_action;

    /**
     * _result  
     * 
     * @var ActionResult
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
     * @param AbstractAction     $action
     * @param InterceptorManager $manager
     */
    public function __construct(AbstractAction $action, InterceptorManager $manager)
    {
        $this->_action = $action;
        $this->_manager = $manager;
        $this->_result = new ActionResult('blank');
        $this->_result->setViewClass('TE\Mvc\View\Blank');
    }

    /**
     * getAction  
     * 
     * @access public
     * @return AbstractAction
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
                $this->_action->{$key} = $val;
            }

            $result = $this->_action->execute();

            if (NULL === $result) {
                $result = 'blank';
            }

            $viewName = is_array($result) ? array_shift($result) : $result;
            $params = is_array($result) ? $result : array();
            $this->_result->setViewName($viewName);
            $this->_result->setParams($params);

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
     * @return ActionResult
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
     * @return ActionEvent
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

