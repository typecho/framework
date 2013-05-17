<?php

namespace TE\Mvc\View;

use TE\Mvc\Action\ActionEvent as Event;
use TE\Mvc\Server\ResponseInterface as Response;

/**
 * Error 
 * 
 * @uses AbstractView
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Error extends Template
{
    /**
     * _content 
     * 
     * @var mixed
     * @access private
     */
    private $_content;

    /**
     * @param Event  $event
     * @param string $content   错误信息
     * @param null   $template  错误模板
     * @param string $prefix    模板前缀
     */
    public function __construct(Event $event, $content = 'Error found', $template = NULL, $prefix = '')
    {
        if (!empty($template)) {
            $event->setData('content', $content);
            parent::__construct($event, $template, $prefix);
        } else {
            $this->_content = $content;
        }
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
        $response->setStatusCode(500);
    }

    /**
     * render  
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        if (!empty($this->_content)) {
            echo $this->_content;
        } else {
            parent::render();
        }
    }
}

