<?php

namespace TE\Mvc\Controller\Interceptor;

/**
 * InterceptorManager 
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class InterceptorManager
{
    /**
     * _stack  
     * 
     * @var array
     * @access private
     */
    private $_stack = array();

    /**
     * _pos  
     * 
     * @var integer
     * @access private
     */
    private $_pos = 0;

    /**
     * @param InterceptorInterface $interceptor
     * @return $this
     */
    public function push(InterceptorInterface $interceptor)
    {
        $this->_stack[] = $interceptor;
        return $this;
    }

    /**
     * fetch  
     * 
     * @access public
     * @return InterceptorInterface
     */
    public function fetch()
    {
        if (isset($this->_stack[$this->_pos])) {
            $interceptor = $this->_stack[$this->_pos];
            $this->_pos ++;
            return $interceptor;
        }

        $this->_pos = 0;
        return NULL;
    }
}

