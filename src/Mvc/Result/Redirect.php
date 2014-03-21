<?php

namespace TE\Mvc\Result;

use TE\Mvc\Response;

/**
 * redirect
 * 
 * @uses AbstractResult
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Redirect extends AbstractResult
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
        $response->setStatusCode($this->getParam(1, false) ? 301 : 302)
            ->setHeader('Location', $this->getParam(0));
    }

    /**
     * render  
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        echo '<h1>Moved ' . ($this->getParam(1, false) ? 'permanently' : 'temporarily') . '</h1>'
            . '<p>Click the <a href="' . $this->getParam(0) . '">url</a> to redirect</p>';
    }
}

