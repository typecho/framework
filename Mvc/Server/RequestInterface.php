<?php

namespace TE\Mvc\Server;

/**
 * RequestInterface  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
interface RequestInterface
{
    /**
     * getPathInfo  
     * 
     * @access public
     * @return string
     */
    public function getPathInfo();

    /**
     * get params
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = NULL);

    /**
     * @param mixed $key
     * @return mixed
     */
    public function getArray($key);

    /**
     * setParams  
     * 
     * @param array $params 
     * @access public
     * @return void
     */
    public function setParams(array $params);
}

