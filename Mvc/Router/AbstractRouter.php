<?php

namespace TE\Mvc\Router;

use TE\Mvc\Base;
use TE\Mvc\Server\RequestInterface as Request;
use TE\Mvc\Server\ResponseInterface as Response;

/**
 * AbstractRouter 
 * 
 * @uses Base
 * @uses RouterInterface
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractRouter extends Base implements RouterInterface
{
    /**
     * _exceptionHandler  
     * 
     * @var string
     * @access private
     */
    private $_exceptionHandler = array('TE\Mvc\Action\ExceptionHandler', array(), array());

    /**
     * createResult  
     * 
     * @param mixed $found 
     * @access private
     * @return void
     */
    protected function createResult($found)
    {
        if (is_array($found)) {
            return new RouterResult($found['action'],
                isset($found['params']) ? $found['params'] : array(),
                isset($found['interceptors']) ? $found['interceptors'] : NULL);
        } else {
            return new RouterResult($found);
        }
    }

    /**
     * route  
     * 
     * @param Request $request 
     * @param Response $response 
     * @access public
     * @return void
     */
    abstract public function route(Request $request, Response $response);

    /**
     * setExceptionHandler 
     * 
     * @param mixed $exceptionHandlerClass 
     * @param array $params 
     * @param mixed $interceptors 
     * @access public
     * @return void
     */
    public function setExceptionHandler($exceptionHandlerClass, array $params = array(), $interceptors = array())
    {
        $this->_exceptionHandler = array($exceptionHandlerClass, $params, $interceptors);
    }

    /**
     * getExceptionResult 
     * 
     * @param \Exception $e 
     * @access public
     * @return void
     */
    public function getExceptionResult(\Exception $e)
    {
        list ($exceptionHandlerClass, $params, $interceptors) = $this->_exceptionHandler;
        $params['exception'] = $e;

        $result = $this->createResult(array(
            'action'        =>  $exceptionHandlerClass,
            'params'        =>  $params,
            'interceptors'  =>  $interceptors
        ));

        return $result;
    }
}

