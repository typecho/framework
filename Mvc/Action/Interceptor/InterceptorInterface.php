<?php

namespace TE\Mvc\Action\Interceptor;

use TE\Mvc\Action\ActionEvent as Event;

/**
 * InterceptorInterface  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
interface InterceptorInterface
{
    /**
     * intercept  
     * 
     * @param Event $event 
     * @access public
     * @return void
     */
    public function intercept(Event $event);
}

