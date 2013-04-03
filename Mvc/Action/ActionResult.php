<?php

namespace TE\Mvc\Action;

/**
 * ActionResult  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class ActionResult
{
    /**
     * _viewName  
     * 
     * @var mixed
     * @access private
     */
    private $_viewName;

    /**
     * _params  
     * 
     * @var mixed
     * @access private
     */
    private $_params;

    /**
     * _viewClass
     * 
     * @var mixed
     * @access private
     */
    private $_viewClass;

    /**
     * @param       $viewName
     * @param array $params
     */
    public function __construct($viewName, array $params = array())
    {
        $this->_viewName = $viewName;
        $this->_viewClass = $viewName;
        $this->_params = $params;
    }

    /**
     * getViewName  
     * 
     * @access public
     * @return string
     */
    public function getViewName()
    {
        return $this->_viewName;
    }

    /**
     * setViewName  
     * 
     * @param mixed $viewName 
     * @access public
     * @return void
     */
    public function setViewName($viewName)
    {
        if ($this->_viewName == $this->_viewClass) {
            $this->_viewClass = $viewName;
        }

        $this->_viewName = $viewName;
    }

    /**
     * getParams  
     * 
     * @access public
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * setParams 
     * 
     * @param array $params 
     * @access public
     * @return void
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
    }

    /**
     * getViewClass  
     * 
     * @access public
     * @return string
     */
    public function getViewClass()
    {
        return $this->_viewClass;
    }

    /**
     * setViewClass  
     * 
     * @param mixed $viewClass 
     * @access public
     * @return void
     */
    public function setViewClass($viewClass)
    {
        $this->_viewClass = $viewClass;
    }
}

