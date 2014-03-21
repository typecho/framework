<?php

namespace TE\Mvc\Result;

use TE\Mvc\Response;

/**
 * display content directly
 * 
 * @uses AbstractResult
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Content extends AbstractResult
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
        $response->setContentType($this->getParam(1, 'text/html'));
    }

    /**
     * render 
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        echo $this->getParam(0);
    }
}

