<?php

namespace TE\Mvc\Server;

use TE\Mvc\Router\RouterInterface as Router;
use TE\Mvc\Router\RouterResult;
use TE\Mvc\Action\Interceptor\InterceptorManager;

/**
 * AbstractServer 
 * 
 * @uses ServerInterface
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractServer implements ServerInterface
{
    /**
     * _request  
     * 
     * @var mixed
     * @access private
     */
    private $_request;

    /**
     * _response  
     * 
     * @var mixed
     * @access private
     */
    private $_response;

    /**
     * _router  
     * 
     * @var mixed
     * @access private
     */
    private $_router;

    /**
     * _manager  
     * 
     * @var mixed
     * @access private
     */
    private $_manager;

    /**
     * @param Router             $router
     * @param InterceptorManager $manager
     */
    public function __construct(Router $router, InterceptorManager $manager)
    {
        $this->_router = $router;
        $this->_manager = $manager;
        $this->_request = $this->createRequest();
        $this->_response = $this->createResponse();

        $this->serve();
    }

    /**
     * createRequest  
     * 
     * @abstract
     * @access protected
     * @return RequestInterface
     */
    abstract protected function createRequest();

    /**
     * createResponse  
     * 
     * @abstract
     * @access protected
     * @return ResponseInterface
     */
    abstract protected function createResponse();

    /**
     * 执行回调
     */
    protected function serve()
    {
        // find action by request
        $result = $this->_router->route($this->_request, $this->_response);

        try {
            $this->executeAction($result);
        } catch (\Exception $e) {
            // inject exception to action
            $result = $this->_router->getExceptionResult($e);
            $this->executeAction($result);
        }

        // respond to client
        $this->_response->respond();
    }


    /**
     * 执行一个Action
     *
     * @param RouterResult $result
     */
    protected function executeAction(RouterResult $result)
    {
        $actionName = $result->getAction();
        $interceptors = $result->getInterceptors();
        $params = $result->getParams();

        // create new interceptor stack list
        foreach ($interceptors as $key => $val) {
            if (is_string($key) && is_array($val)) {
                $this->_manager->push($key, $val);
            } else {
                $this->_manager->push($val, array());
            }
        }

        $action = new $actionName($this->_request, $this->_response, $this->_manager);

        foreach ($params as $key => $val) {
            $method = 'set' . ucfirst($key);

            if (method_exists($action, $method)) {
                $action->{$method}($val);
            }
        }

        $action->handle();
    }
}

