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
     * _resultName
     * 
     * @var mixed
     * @access private
     */
    private $_resultName;

    /**
     * _params  
     * 
     * @var mixed
     * @access private
     */
    private $_params;

    /**
     * _resultClass
     * 
     * @var mixed
     * @access private
     */
    private $_resultClass;

    /**
     * @param       $resultName
     * @param array $params
     */
    public function __construct($resultName, array $params = array())
    {
        $this->_resultName = $resultName;
        $this->_resultClass = $resultName;
        $this->_params = $params;
    }

    /**
     * getResultName
     * 
     * @access public
     * @return string
     */
    public function getResultName()
    {
        return $this->_resultName;
    }

    /**
     * setResultName
     * 
     * @param mixed $resultName
     * @access public
     * @return void
     */
    public function setResultName($resultName)
    {
        if ($this->_resultName == $this->_resultClass) {
            $this->_resultClass = $resultName;
        }

        $this->_resultName = $resultName;
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
     * getResultClass
     * 
     * @access public
     * @return string
     */
    public function getResultClass()
    {
        return $this->_resultClass;
    }

    /**
     * setResultClass
     * 
     * @param mixed $resultClass
     * @access public
     * @return void
     */
    public function setResultClass($resultClass)
    {
        $this->_resultClass = $resultClass;
    }
}

