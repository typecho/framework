<?php

namespace TE\Mvc\Action;

use TE\System;
use TE\Mvc\Server\RequestInterface as Request;
use TE\Mvc\Server\ResponseInterface as Response;
use TE\Mvc\Base;
use TE\Mvc\Action\Interceptor\InterceptorManagerInterface as InterceptorManager;

/**
 * AbstractAction
 * 
 * @uses Base
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractAction extends Base
{
    /**
     * request 
     * 
     * @var mixed
     * @access protected
     */
    protected $request;

    /**
     * response  
     * 
     * @var mixed
     * @access protected
     */
    protected $response;

    /**
     * _event  
     * 
     * @var mixed
     * @access private
     */
    private $_event;

    /**
     * __construct 
     * 
     * @param Request $request 
     * @param Response $response 
     * @param InterceptorStack $interceptorStack 
     * @final
     * @access public
     * @return void
     */
    public final function __construct(Request $request, Response $response, InterceptorManager $interceptorManager)
    {
        $this->request = $request;
        $this->response = $response;
        $this->_event = new ActionEvent($this, $interceptorManager);

        parent::__construct();
    }

    /**
     * handle 
     * 
     * @param InterceptorStack $interceptorStack 
     * @access public
     * @return void
     */
    public function handle()
    {
        $this->_event->invoke();

        $result = $this->_event->getResult();
        $viewClass = $result->getViewClass();
        $params = $result->getParams();
        array_unshift($params, $this->_event);
        $viewRefelect = new \ReflectionClass($viewClass);
        $view = $viewRefelect->newInstanceArgs($params);

        $this->response->setView($view);
    }

    /**
     * getRequest  
     * 
     * @access public
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * getResponse  
     * 
     * @access public
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * execute  
     * 
     * @abstract
     * @access public
     * @return mixed
     */
    abstract public function execute();
}

