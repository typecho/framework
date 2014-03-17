<?php

namespace TE\Mvc\Result;

use TE\Mvc\Server\ResponseInterface as Response;

/**
 * AbstractResult
 * 
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractResult
{
    /**
     * 必须实现渲染方法
     * 
     * @abstract
     * @access public
     * @return void
     */
    abstract public function render();

    /**
     * 可选实现的准备response方法 
     * 
     * @param Response $response 
     * @access public
     * @return void
     */
    public function prepareResponse(Response $response)
    {}
}

