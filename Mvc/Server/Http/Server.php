<?php

namespace TE\Mvc\Server\Http;

use TE\Mvc\Action\Interceptor\InterceptorManager;
use TE\Mvc\Server\AbstractServer;
use TE\Mvc\Settings;

/**
 * Server  
 * 
 * @uses AbstractServer
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Server extends AbstractServer
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * createRequest  
     * 
     * @access protected
     * @return Request
     */
    protected function createRequest()
    {
        return new Request();
    }

    /**
     * createResponse  
     * 
     * @access protected
     * @return Response
     */
    protected function createResponse()
    {
        return new Response();
    }

    /**
     * 路由实现
     *
     * @param array $routes
     * @return string
     */
    protected function route(array $routes)
    {
        $pathInfo = $this->request->getPathInfo();

        foreach ($routes as $route => $action) {
            $routeParts = parse_url($route);

            $params = array();
            $route = preg_replace_callback("/\[([_a-z]+)\]/i", function ($matches) use (&$params) {
                $params[] = $matches[1];
                return '%';
            }, $routeParts['path']);

            $route = str_replace('%', '([^\/]+)', preg_quote($route));
            if (preg_match('|^' . $route . '$|', $pathInfo, $matches)) {
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

                return $action;
            }
        }
    }
}
