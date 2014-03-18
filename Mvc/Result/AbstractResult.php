<?php

namespace TE\Mvc\Result;

use TE\Mvc\Action\ActionEvent;
use TE\Mvc\Server\ResponseInterface as Response;

/**
 * AbstractResult
 * 
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractResult
{
    /**
     * @var ActionEvent
     */
    private $_event;

    /**
     * @var array
     */
    private $_params = array();

    /**
     * 必须实现渲染方法
     * 
     * @abstract
     * @access public
     * @return void
     */
    abstract public function render();

    /**
     * @param ActionEvent $event
     */
    final public function setEvent(ActionEvent $event)
    {
        $this->_event = $event;
    }

    /**
     * @return ActionEvent
     */
    final public function getEvent()
    {
        return $this->_event;
    }

    /**
     * @param array $params
     */
    final public function setParams(array $params)
    {
        $this->_params = $params;
    }

    /**
     * @param integer $pos
     * @param mixed $value
     */
    final public function setParam($pos, $value)
    {
        $this->_params[$pos] = $value;
        ksort($this->pos);
    }

    /**
     * @param integer $pos
     * @param mixed $default
     * @return mixed
     */
    final public function getParam($pos, $default = NULL)
    {
        return isset($this->_params[$pos]) ? $this->_params[$pos] : $default;
    }

    /**
     * init some data
     */
    public function init()
    {}

    /**
     * 可选实现的准备response方法 
     * 
     * @param Response $response 
     * @access public
     * @return void
     */
    public function prepareResponse(Response $response)
    {}
}

