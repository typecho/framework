<?php

namespace TE\Mvc\View;

use TE\System;
use TE\Mvc\Server\ResponseInterface as Response;
use TE\Mvc\Action\ActionEvent as Event;

/**
 * TemplateView  
 * 
 * @uses AbstractView
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class TemplateView extends AbstractView
{
    /**
     * _template  
     * 
     * @var string
     * @access private
     */
    private $_template = '';

    /**
     * _prefix  
     * 
     * @var string
     * @access private
     */
    private $_prefix = '';

    /**
     * _vars  
     * 
     * @var array
     * @access protected
     */
    protected $vars = array();

    /**
     * __construct 
     * 
     * @param Event $event 
     * @param mixed $template 
     * @param mixed $prefix 
     * @access public
     * @return void
     */
    public function __construct(Event $event, $template, $prefix = '')
    {
        $this->vars = $event->getData();
        $this->_template = $template;
        $this->_prefix = $prefix;
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
            ->setContentType('text/html');
    }

    /**
     * render 
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        global $template;

        $file = $this->_template;
        $data = $this->vars;
        $prefix = $this->_prefix;

        $template = function ($file) use ($data, $prefix) {
            global $template;

            extract($data);
            require $prefix . $file;
        };

        $template($file);
    }
}

