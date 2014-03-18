<?php

namespace TE\Mvc\Server;

use TE\Mvc\Router\RouterInterface as Router;
use TE\Mvc\Router\RouterResult;
use TE\Mvc\Controller\Interceptor\InterceptorManager;
use TE\Mvc\Settings;

/**
 * AbstractServer 
 * 
 * @uses ServerInterface
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractServer
{
    /**
     * _request  
     * 
     * @var mixed
     * @access protected
     */
    protected  $request;

    /**
     * _response  
     * 
     * @var mixed
     * @access protected
     */
    protected $response;

    /**
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->request = $this->createRequest();
        $this->response = $this->createResponse();
        $controller = $this->route($routes);

        $this->serve($controller);
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
     * 路由实现
     *
     * @param array $routes
     * @return string
     */
    abstract protected function route(array $routes);

    /**
     * 执行回调
     *
     * @param string $controller
     */
    protected function serve($controller)
    {
        try {
            if (empty($controller)) {
                $controller = Settings::routerNotFound();
            }

            $parts = parse_url($controller);
            $controller = $parts['path'];
            $method = 'execute';
            $interceptorManager = new InterceptorManager();

            if (!empty($parts['scheme'])) {
                $interceptors = explode('+', $parts['scheme']);
                foreach ($interceptors as $interceptor) {
                    Settings::pushInterceptor($interceptorManager, $interceptor);
                }
            }

            if (!empty($parts['fragment'])) {
                $method = $parts['fragment'];
            }

            $controllerInstance = new $controller($this->request, $this->response, $interceptorManager);

            if (!empty($parts['query'])) {
                parse_str($parts['query'], $params);

                foreach ($params as $key => $val) {
                    $controllerInstance->{$key} = $val;
                }
            }

            $controllerInstance->handle($method);

        } catch (\Exception $e) {
            Settings::catchException($e);
        }

        $this->response->respond();
    }
}

