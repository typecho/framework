<?php

namespace TE\Mvc\Action\Interceptor;

use TE\Mvc\Action\ActionEvent as Event;

/**
 * Template 
 * 
 * @uses InterceptorInterface
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Template implements InterceptorInterface
{
    /**
     * _path
     * 
     * @var string
     * @access private
     */
    private $_path = './template/';

    /**
     * intercept  
     * 
     * @param Event $event 
     * @access public
     * @return void
     */
    public function intercept(Event $event)
    {
        $event->invoke();
        $result = $event->getResult();
        $name = $result->getViewName();
        $params = $result->getParams();

        if ('template' == $name) {
            $params[1] = $this->_path;
        } else if (('notFound' == $name || 'error' == $name) && !empty($params[1])) {
            $params[2] = $this->_path;
        }

        $result->setParams($params);
    }

    /**
     * setPath  
     * 
     * @param mixed $path 
     * @access public
     * @return void
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }
}

