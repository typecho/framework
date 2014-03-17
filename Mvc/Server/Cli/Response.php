<?php

namespace TE\Mvc\Server\Cli;

use TE\Mvc\Server\ResponseInterface;
use TE\Mvc\Result\AbstractResult as Result;

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
     * _result
     * 
     * @var mixed
     * @access private
     */
    private $_result;

    /**
     * respond  
     * 
     * @access public
     */
    public function respond()
    {
        if (NULL !== $this->_result) {
            $this->_result->render();
        }
    }

    /**
     * setResult
     * 
     * @param Result $result
     * @access public
     * @return Response
     */
    public function setResult(Result $result)
    {
        $this->_result = $result;
        return $this;
    }
}

