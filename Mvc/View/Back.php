<?php

namespace TE\Mvc\View;

/**
 * 往回跳转
 * 
 * @uses AbstractView
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Back extends Redirect
{
    /**
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        parent::__construct($event, $event->getAction()->getRequest()->getReferer());
    }
}

