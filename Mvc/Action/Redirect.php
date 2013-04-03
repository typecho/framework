<?php

namespace TE\Mvc\Action;

/**
 * Redirect
 * 
 * @uses AbstractAction
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Redirect extends AbstractAction
{
    /**
     * _url
     * 
     * @var mixed
     * @access private
     */
    private $_url;

    /**
     * execute  
     * 
     * @access public
     * @return mixed
     */
    public function execute()
    {
        $request = $this->request;
        $url = preg_replace_callback("/\{([_a-z0-9-]+)\}/i", function ($matches) use ($request) {
            return $request->get($matches[1]);
        }, $this->_url);

        return array('redirect', $url);
    }

    /**
     * setUrl
     * 
     * @param mixed $url
     * @access public
     * @return void
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }
}

