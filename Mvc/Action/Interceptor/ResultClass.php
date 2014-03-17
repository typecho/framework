<?php

namespace TE\Mvc\Action\Interceptor;

use TE\Mvc\Action\ActionEvent as Event;

/**
 * ResultClass
 * 
 * @uses InterceptorInterface
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class ResultClass implements InterceptorInterface
{
    /**
     * _resultClasses
     * 
     * @var array
     * @access private
     */
    private $_resultClasses = array(
        'back'              =>  'TE\Mvc\Result\Back',
        'blank'             =>  'TE\Mvc\Result\Blank',
        'content'           =>  'TE\Mvc\Result\Content',
        'error'             =>  'TE\Mvc\Result\Error',
        'json'              =>  'TE\Mvc\Result\Json',
        'jsonp'             =>  'TE\Mvc\Result\Jsonp',
        'notFound'          =>  'TE\Mvc\Result\NotFound',
        'redirect'          =>  'TE\Mvc\Result\Redirect',
        'template'          =>  'TE\Mvc\Result\Template'
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
        $name = $result->getResultName();

        if (isset($this->_resultClasses[$name])) {
            $result->setResultClass($this->_resultClasses[$name]);
        }
    }

    /**
     * setResultClass
     * 
     * @param array $resultClass
     * @access public
     * @return void
     */
    public function setResultClass(array $resultClass)
    {
        $this->_resultClasses = array_merge($this->_resultClasses, $resultClass);
    }
}

