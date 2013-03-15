<?php

namespace TE\Mvc\Action\Interceptor;

use TE\Mvc\Action\ActionEvent as Event;

/**
 * TemplateInterceptor 
 * 
 * @uses InterceptorInterface
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class TemplateInterceptor implements InterceptorInterface
{
    /**
     * _path
     * 
     * @var string
     * @access private
     */
    private $_path = '/template/';

    /**
     * _notFound  
     * 
     * @var string
     * @access private
     */
    private $_notFound = '404.php';

    /**
     * _error  
     * 
     * @var string
     * @access private
     */
    private $_error = '500.php';

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

        switch ($name) {
            case 'template':
                if (!isset($params[1])) {
                    $params[1] = $this->_path;
                }
                break;
            case 'error':
                if (!isset($params[0])) {
                    $params[0] = 'Server error';
                }

                if (!isset($params[1])) {
                    $params[1] = $this->_error;
                }

                if (!isset($params[2])) {
                    $params[2] = $this->_path;
                }
                break;
            case 'notFound':
                if (!isset($params[0])) {
                    $params[0] = 'Page not found';
                }

                if (!isset($params[1])) {
                    $params[1] = $this->notFound;
                }

                if (!isset($params[2])) {
                    $params[2] = $this->_path;
                }
                break;
            default:
                break;
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

    /**
     * setNotFound 
     * 
     * @param mixed $notFound 
     * @access public
     * @return void
     */
    public function setNotFound($notFound)
    {
        $this->_notFound = $notFound;
    }

    /**
     * setError  
     * 
     * @param mixed $error 
     * @access public
     * @return void
     */
    public function setError($error)
    {
        $this->_error = $error;
    }
}

