<?php

namespace TE\Mvc\Result;

use TE\Mvc\Response;

/**
 * Json  
 * 
 * @uses AbstractResult
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Json extends AbstractResult
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
        $response->setStatusCode(200)
            ->setHeader('Cache-Control', 'no-cache')
            ->setContentType('application/json');
    }

    /**
     * render  
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        echo json_encode($this->getParam(0));
    }
}

