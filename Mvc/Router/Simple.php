<?php

namespace TE\Mvc\Router;

use TE\Mvc\Base;
use TE\Mvc\Server\RequestInterface as Request;
use TE\Mvc\Server\ResponseInterface as Response;

/**
 * Simple
 * 
 * @uses RouterInterface
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Simple extends Base implements RouterInterface
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
     * createResult  
     * 
     * @param mixed $found 
     * @access private
     * @return void
     */
    private function createResult($found)
    {
        if (is_array($found)) {
            return new RouterResult($found['action'],
                isset($found['params']) ? $found['params'] : array(),
                isset($found['interceptors']) ? $found['interceptors'] : array());
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

    /**
     * getExceptionResult 
     * 
     * @param \Exception $e 
     * @access public
     * @return void
     */
    public function getExceptionResult(\Exception $e)
    {
        $result = $this->createResult(array(
            'action'    =>  isset($routes['exception']) 
                ? $routes['exception'] : 'TE\Mvc\Action\ExceptionHandler',
            'params'    =>  array('exception' =>  $e)
        ));
        return $result;
    }
}

