<?php

namespace TE\Mvc\View;

use TE\System;
use TE\Mvc\Server\ResponseInterface as Response;
use TE\Mvc\Action\ActionEvent as Event;

/**
 * ContentView  
 * 
 * @uses AbstractView
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class ContentView extends AbstractView
{
    /**
     * _content 
     * 
     * @var mixed
     * @access private
     */
    private $_content;

    /**
     * _contentType 
     * 
     * @var mixed
     * @access private
     */
    private $_contentType;

    /**
     * __construct 
     * 
     * @param Event $event 
     * @param mixed $template 
     * @access public
     * @return void
     */
    public function __construct(Event $event, $content, $contentType = 'text/html')
    {
        $this->_content = $content;
        $this->_contentType = $contentType;
    }

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
            ->setContentType($this->_contentType);
    }

    /**
     * render 
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        echo $this->_content;
        exit;
    }
}

