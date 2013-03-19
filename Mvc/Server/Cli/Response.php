<?php

namespace TE\Mvc\Server\Cli;

use TE\Mvc\Server\ResponseInterface;
use TE\Mvc\View\AbstractView as View;

/**
 * Response 
 * 
 * @uses ResponseInterface
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Response implements ResponseInterface
{
    /**
     * _view  
     * 
     * @var mixed
     * @access private
     */
    private $_view;

    /**
     * respond  
     * 
     * @access public
     * @return void
     */
    public function respond()
    {
        if (NULL !== $this->_view) {
            $this->_view->render();
        }
    }

    /**
     * setView  
     * 
     * @param View $view 
     * @access public
     * @return void
     */
    public function setView(View $view)
    {
        $this->_view = $view;
        return $this;
    }
}

