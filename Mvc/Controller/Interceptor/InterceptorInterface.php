<?php

namespace TE\Mvc\Controller\Interceptor;

use TE\Mvc\Controller\ControllerEvent;

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
     * @param ControllerEvent $event
     */
    public function intercept(ControllerEvent $event);
}

