<?php

namespace TE\Mvc\Result;

use TE\Mvc\Response;

/**
 * 404 page
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

