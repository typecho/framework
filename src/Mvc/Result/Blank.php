<?php

namespace TE\Mvc\Result;

use TE\Mvc\Response;

/**
 * blank page
 * 
 * @uses AbstractResult
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Blank extends AbstractResult
{
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
    {}
}

