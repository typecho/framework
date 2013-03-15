<?php

namespace TE\Mvc\Server;

use TE\Mvc\View\AbstractView as View;

/**
 * ResponseInterface  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
interface ResponseInterface
{
    /**
     * setView  
     * 
     * @param View $view 
     * @access public
     * @return void
     */
    public function setView(View $view);

    /**
     * respond  
     * 
     * @access public
     * @return void
     */
    public function respond();
}

