<?php

namespace TE\Mvc\Result;

use TE\Mvc\Server\ResponseInterface as Response;
use TE\Mvc\Action\ActionEvent as Event;

/**
 * 空白页
 * 
 * @uses AbstractResult
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Blank extends AbstractResult
{
    /**
     * __construct  
     * 
     * @param Event $event 
     * @access public
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
    {}

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

