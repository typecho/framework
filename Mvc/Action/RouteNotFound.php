<?php

namespace TE\Mvc\Action;

/**
 * RouteNotFound
 * 
 * @uses AbstractAction
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class RouteNotFound extends AbstractAction
{
    /**
     * _content
     * 
     * @var mixed
     * @access private
     */
    private $_content;

    /**
     * execute  
     * 
     * @access public
     * @return void
     */
    public function execute()
    {
        return array('notFound', $this->_content);
    }

    /**
     * setContent
     * 
     * @param mixed $content
     * @access public
     * @return void
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }
}

