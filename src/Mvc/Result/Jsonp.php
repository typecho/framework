<?php

namespace TE\Mvc\Result;

use TE\Mvc\Response;

/**
 * Jsonp
 * 
 * @uses AbstractResult
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Jsonp extends AbstractResult
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
            ->setContentType('text/javascript');
    }

    /**
     * render  
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        $callback = $this->getEvent()->getController()
            ->getRequest()->get($this->getParam(1, 'callback'), 'jsonp');
        echo $callback . '(' . json_encode($this->getParam(0)) . ')';
    }
}

