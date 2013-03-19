<?php

namespace TE\Mvc\Action\Interceptor;

use TE\Mvc\Action\ActionEvent as Event;

/**
 * ViewClass  
 * 
 * @uses InterceptorInterface
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class ViewClass implements InterceptorInterface
{
    /**
     * _viewClasses 
     * 
     * @var array
     * @access private
     */
    private $_viewClasses = array(
        'blank'             =>  'TE\Mvc\View\Blank',
        'content'           =>  'TE\Mvc\View\Content',
        'error'             =>  'TE\Mvc\View\Error',
        'json'              =>  'TE\Mvc\View\Json',
        'jsonp'             =>  'TE\Mvc\View\Jsonp',
        'notFound'          =>  'TE\Mvc\View\NotFound',
        'redirect'          =>  'TE\Mvc\View\Redirect',
        'template'          =>  'TE\Mvc\View\Template'
    );

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

        if (isset($this->_viewClasses[$name])) {
            $result->setViewClass($this->_viewClasses[$name]);
        }
    }

    /**
     * setViewClass  
     * 
     * @param array $viewClass 
     * @access public
     * @return void
     */
    public function setViewClass(array $viewClass)
    {
        $this->_viewClasses = array_merge($this->_viewClasses, $viewClass);
    }
}

