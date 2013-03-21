<?php

namespace TE\Mvc\View;

use TE\System;
use TE\Mvc\Server\ResponseInterface as Response;
use TE\Mvc\Action\ActionEvent as Event;

/**
 * Template  
 * 
 * @uses AbstractView
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Template extends AbstractView
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
    {}

    /**
     * render 
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        global $template;

        $_file = $this->_template;
        $_data = $this->vars;
        $_prefix = $this->_prefix;

        $template = function ($_file) use ($_data, $_prefix) {
            global $template;

            extract($_data);
            $_files = is_array($_file) ? $_file : array($_file);

            foreach ($_files as $_file) {
                $_file = $_prefix . $_file;
                if (file_exists($_file)) {
                    require $_file;
                    return;
                }
            }

            throw new \Exception('Template file not found');
        };

        $template($_file);
        exit;
    }
}

