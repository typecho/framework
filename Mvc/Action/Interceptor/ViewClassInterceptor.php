<?php

namespace TE\Mvc\Action\Interceptor;

use TE\Mvc\Action\ActionEvent as Event;

/**
 * ViewClassInterceptor  
 * 
 * @uses InterceptorInterface
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class ViewClassInterceptor implements InterceptorInterface
{
    /**
     * _viewClasses 
     * 
     * @var array
     * @access private
     */
    private $_viewClasses = array(
        'content'           =>  'TE\Mvc\View\ContentView',
        'empty'             =>  'TE\Mvc\View\EmptyView',
        'error'             =>  'TE\Mvc\View\ErrorView',
        'json'              =>  'TE\Mvc\View\JsonView',
        'jsonp'             =>  'TE\Mvc\View\JsonpView',
        'notFound'          =>  'TE\Mvc\View\NotFoundView',
        'redirect'          =>  'TE\Mvc\View\RedirectView',
        'template'          =>  'TE\Mvc\View\TemplateView'
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

