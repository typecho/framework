<?php

namespace TE\Mvc\Server\Http;

use TE\Mvc\Server\AbstractServer;

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
     * createRequest  
     * 
     * @access protected
     * @return void
     */
    protected function createRequest()
    {
        return new Request();
    }

    /**
     * createResponse  
     * 
     * @access protected
     * @return void
     */
    protected function createResponse()
    {
        return new Response();
    }
}

