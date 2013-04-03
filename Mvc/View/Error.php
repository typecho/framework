<?php

namespace TE\Mvc\View;

use TE\Mvc\Server\Http\Response;
use TE\Mvc\Action\ActionEvent as Event;

/**
 * Error 
 * 
 * @uses AbstractView
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Error extends AbstractView
{
    /**
     * _content 
     * 
     * @var mixed
     * @access private
     */
    private $_content;

    /**
     * _template  
     * 
     * @var mixed
     * @access private
     */
    private $_template;

    /**
     * _data  
     * 
     * @var mixed
     * @access private
     */
    private $_data;

    /**
     * @param Event  $event
     * @param string $content   错误信息
     * @param null   $template  错误模板
     */
    public function __construct(Event $event, $content = 'Error found', $template = NULL)
    {
        $this->_data = $event->getData();
        $this->_data['content'] = $content;
        $this->_template = $template;
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
        if ($this->_template && file_exists($this->_template)) {
            extract($this->_data);
            require $this->_template;
        } else {
            echo $this->_data['content'];
        }
    }
}

