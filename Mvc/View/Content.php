<?php

namespace TE\Mvc\View;

use TE\Mvc\Server\ResponseInterface as Response;
use TE\Mvc\Action\ActionEvent as Event;

/**
 * 渲染一个字符串
 * 
 * @uses AbstractView
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Content extends AbstractView
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
     * @param Event  $event
     * @param        $content       渲染的字符串
     * @param string $contentType   页面类型
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

