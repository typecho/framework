<?php

namespace TE\Mvc;

use TE\Mvc\Controller\Interceptor\InterceptorManager;
use TE\Settings;

/**
 * Server
 * 
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Server
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
        $this->request = new Request();
        $this->response = new Response();
        $controller = $this->route($routes);

        $this->serve($controller);
    }

    /**
     * implements route
     *
     * @param array $routes
     * @return string
     */
    protected function route(array $routes)
    {
        $pathInfo = $this->request->getPathInfo();

        foreach ($routes as $route => $controller) {
            $routeParts = parse_url($route);

            $params = array();
            $route = preg_replace_callback("/\[([_a-z]+)\]/i", function ($matches) use (&$params) {
                $params[] = $matches[1];
                return '%';
            }, $routeParts['path']);

            $route = str_replace('%', '([^\/]+)', preg_quote($route));
            if (preg_match('|^' . $route . '$|u', $pathInfo, $matches)) {
                if (!empty($routeParts['scheme'])) {
                    $method = 'is' . ucfirst($routeParts['scheme']);
                    if (method_exists($this->request, $method) && !$this->request->{$method}()) {
                        continue;
                    }
                }

                if (!empty($routeParts['query']) && !$this->request->is($routeParts['query'])) {
                    continue;
                }

                array_shift($matches);
                if (!empty($params)) {
                    $params = array_combine($params, $matches);
                    $this->request->setParams($params);
                }

                return $controller;
            }
        }
    }

    /**
     * handle controller
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
            $this->response->respond();

        } catch (\Exception $e) {
            Settings::catchException($e);
        }
    }
}

