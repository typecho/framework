<?php

namespace TE\Mvc\Server;

use TE\Mvc\Result\AbstractResult as Result;

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
     * setResult
     * 
     * @param Result $result
     * @access public
     * @return void
     */
    public function setResult(Result $result);

    /**
     * respond  
     * 
     * @access public
     * @return void
     */
    public function respond();
}

