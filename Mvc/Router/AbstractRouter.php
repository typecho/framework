<?php

namespace TE\Mvc\Router;

use TE\Mvc\Base;

/**
 * AbstractRouter  
 * 
 * @uses Base
 * @uses RouterInterface
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class AbstractRouter extends Base implements RouterInterface
{
    /**
     * _exceptionHandler  
     * 
     * @var string
     * @access private
     */
    private $_exceptionHandler = 'TE\Mvc\Action\ExceptionHandler';

    /**
     * createResult  
     * 
     * @param mixed $found 
     * @access private
     * @return void
     */
    protected function createResult($found)
    {
        if (is_array($found)) {
            return new RouterResult($found['action'],
                isset($found['params']) ? $found['params'] : array(),
                isset($found['interceptors']) ? $found['interceptors'] : array());
        } else {
            return new RouterResult($found);
        }
    }

    /**
     * setExceptionHandler  
     * 
     * @param mixed $exceptionHandler 
     * @access public
     * @return void
     */
    public function setExceptionHandler($exceptionHandler)
    {
        $this->_exceptionHandler = $exceptionHandler;
    }

    /**
     * getExceptionResult 
     * 
     * @param \Exception $e 
     * @access public
     * @return void
     */
    public function getExceptionResult(\Exception $e)
    {
        $result = $this->createResult(array(
            'action'    =>  isset($routes['exception']) 
                ? $routes['exception'] : $this->_exceptionHandler,
            'params'    =>  array('exception' =>  $e)
        ));
        return $result;
    }
}

