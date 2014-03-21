<?php

namespace TE\Mvc\Result;

/**
 * 往回跳转
 * 
 * @uses AbstractResult
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Back extends Redirect
{
    /**
     * init some data
     */
    public function init()
    {
        $this->setParam(0, $this->getEvent()->getController()->getRequest()->getReferer());
    }
}

