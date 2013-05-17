<?php

namespace TE\Mvc\View;

use TE\Mvc\Server\ResponseInterface as Response;

/**
 * 404错误页
 * 
 * @uses Error
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class NotFound extends Error
{
    /**
     * prepareResponse  
     * 
     * @param Response $response
     * @access public
     * @return void
     */
    public function prepareResponse(Response $response)
    {
        $response->setStatusCode(404);
    }
}

