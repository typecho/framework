<?php

namespace TE\Mvc\Controller;

use TE\Mvc\Form\AbstractForm;
use TE\Mvc\Request;
use TE\Mvc\Response;
use TE\Base;
use TE\Mvc\Controller\Interceptor\InterceptorManager;

/**
 * AbstractController
 * 
 * @uses Base
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractController extends Base
{
    /**
     * request 
     * 
     * @var Request
     * @access protected
     */
    protected $request;

    /**
     * response  
     * 
     * @var Response
     * @access protected
     */
    protected $response;

    /**
     * _event  
     * 
     * @var ControllerEvent
     * @access private
     */
    private $_event;

    /**
     * @param Request            $request
     * @param Response           $response
     * @param InterceptorManager $interceptorManager
     */
    public function __construct(Request $request, Response $response, InterceptorManager $interceptorManager)
    {
        $this->request = $request;
        $this->response = $response;
        $this->_event = new ControllerEvent($this, $interceptorManager);

        parent::__construct();
    }

    /**
     * handle
     *
     * @param string $method
     */
    final public function handle($method)
    {
        $this->_event->invoke($method);
        $this->response->setResult($this->_event->getResult());
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
     * @throws \Exception
     */
    public function execute()
    {
        throw new \Exception("Please implements action 'execute'");
    }

    /**
     * handle form assert
     *
     * @param string $action
     * @param AbstractForm $form
     * @return mixed
     * @throws \Exception
     */
    public function formAssert($action, AbstractForm $form)
    {
        throw new \Exception("Please implements formAssert " . get_class($form) . " from action {$action}");
    }
}

