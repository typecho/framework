<?php

namespace TE\Mvc\Action\Interceptor;

use TE\Mvc\Action\ActionEvent;

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
     * @param ActionEvent $event
     */
    public function intercept(ActionEvent $event);
}

