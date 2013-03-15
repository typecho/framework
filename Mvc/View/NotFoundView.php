<?php

namespace TE\Mvc\View;

use TE\System;
use TE\Mvc\Server\ResponseInterface as Response;
use TE\Mvc\Action\ActionEvent as Event;

/**
 * NotFoundView  
 * 
 * @uses Template
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class NotFoundView extends TemplateView
{
    /**
     * __construct 
     * 
     * @param Event $event 
     * @param string $content 
     * @param string $template 
     * @param string $prefix 
     * @access public
     * @return void
     */
    public function __construct(Event $event, $content = 'Page not found', $template = '404.php', $prefix = '')
    {
        parent::__construct($event, $template);
        $this->vars['content'] = $content;
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
        $response->setStatusCode(404)
            ->setContentType('text/html');
    }
}

