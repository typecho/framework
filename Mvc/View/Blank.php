<?php

namespace TE\Mvc\View;

use TE\Mvc\Server\ResponseInterface as Response;
use TE\Mvc\Action\ActionEvent as Event;

/**
 * Blank
 * 
 * @uses AbstractView
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Blank extends AbstractView
{
    /**
     * __construct  
     * 
     * @param Event $event 
     * @access public
     * @return void
     */
    public function __construct(Event $event)
    {}

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
            ->setContentType('text/html');
    }

    /**
     * render  
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        exit;
    }
}

