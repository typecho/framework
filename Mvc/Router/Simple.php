<?php

namespace TE\Mvc\Router;

use TE\Mvc\Server\RequestInterface as Request;
use TE\Mvc\Server\ResponseInterface as Response;

/**
 * Simple  
 * 
 * @uses AbstractRouter
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Simple extends AbstractRouter
{
    /**
     * _routes  
     * 
     * @var array
     * @access private
     */
    private $_routes = array();

    /**
     * __construct  
     * 
     * @param mixed $routes 
     * @access public
     * @return void
     */
    public function __construct(array $routes = array())
    {
        $this->_routes = $routes;
    }

    /**
     * route  
     * 
     * @param Request $request 
     * @param Response $response 
     * @access public
     * @return void
     */
    public function route(Request $request, Response $response)
    {
        $routes = $this->_routes;
        $pathInfo = '/' . trim($request->getPathInfo(), '/');
        $found = NULL;

        if (isset($routes[$pathInfo])) {
            $found = $routes[$pathInfo];
        } else {
            foreach ($routes as $route => $action) {
                if (false === strpos($route, ':')) {
                    continue;
                }

                $params = array();
                $route = preg_replace_callback('/:([_a-z]+)/i', function ($matches) use (&$params) {
                    $params[] = $matches[1];
                    return '%';
                }, $route);

                $route = str_replace('%', '([^\/]+)', preg_quote($route));
                if (preg_match('|^' . $route . '$|', $pathInfo, $matches)) {
                    array_shift($matches);
                    if (!empty($params)) {
                        $params = array_combine($params, $matches);
                        $request->setParams($params);
                    }

                    $found = $action;
                    break;
                }
            }
        }
        
        if (empty($found)) {
            return $this->createResult(isset($routes['routeNotFound']) 
                ? $routes['routeNotFound'] : 'TE\Mvc\Action\RouteNotFound');
        }

        return $this->createResult($found);
    }
}

