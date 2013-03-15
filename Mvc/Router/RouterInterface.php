<?php

namespace TE\Mvc\Router;

use TE\Mvc\Server\RequestInterface as Request;
use TE\Mvc\Server\ResponseInterface as Response;

/**
 * RouterInterface  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
interface RouterInterface
{
    /**
     * route  
     * 
     * @param Request $request 
     * @param Response $response 
     * @access public
     * @return void
     */
    public function route(Request $request, Response $response);

    /**
     * getExceptionResult 
     * 
     * @param \Exception $e 
     * @access public
     * @return void
     */
    public function getExceptionResult(\Exception $e);
}

