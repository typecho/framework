<?php

namespace TE\Mvc\Action\Interceptor;

/**
 * InterceptorManagerInterface  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
interface InterceptorManagerInterface
{
    /**
     * pushInterceptor
     * 
     * @param mixed $name 
     * @param array $params 
     * @access public
     * @return void
     */
    public function push($name, array $params);

    /**
     * fetch  
     * 
     * @access public
     * @return void
     */
    public function fetch();
}

