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
     * @return void
     */
    public function getPathInfo();

    /**
     * getArgs  
     * 
     * @access public
     * @return void
     */
    public function getArgs();

    /**
     * setParams  
     * 
     * @param array $params 
     * @access public
     * @return void
     */
    public function setParams(array $params);
}

