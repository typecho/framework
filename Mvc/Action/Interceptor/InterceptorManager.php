<?php

namespace TE\Mvc\Action\Interceptor;

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
     * _interceptors  
     * 
     * @var array
     * @access private
     */
    private $_interceptors = array(
        'default'   =>  array(
            'viewClass',
            'template'
        ),
        'template'  =>  array(
            'interceptor'   =>  'TE\Mvc\Action\Interceptor\Template',
            'params'        =>  array()
        ),
        'viewClass' =>  'TE\Mvc\Action\Interceptor\ViewClass'
    );

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
     * @var float
     * @access private
     */
    private $_pos = 0;

    /**
     * __construct  
     * 
     * @param array $interceptors 
     * @access public
     * @return void
     */
    public function __construct(array $interceptors = array())
    {
        $this->_interceptors = array_merge($this->_interceptors, $interceptors);
    }

    /**
     * prepareInterceptor  
     * 
     * @param InterceptorInterface $interceptor 
     * @param array $params 
     * @access private
     * @return void
     */
    private function prepareInterceptor(InterceptorInterface $interceptor, array $params)
    {
        foreach ($params as $key => $val) {
            $method = 'set' . ucfirst($key);
            if (method_exists($interceptor, $method)) {
                $interceptor->{$method}($val);
            }
        }

        return $interceptor;
    }

    /**
     * getParams  
     * 
     * @param mixed $name 
     * @param array $params 
     * @access private
     * @return void
     */
    private function getParams($name, array $params)
    {
        $result = array();
        foreach ($params as $key => $val) {
            if (0 == strpos($key, $name . '.')) {
                $result[substr($key, strlen($name) + 1)] = $val;
            }
        }

        return $result;
    }

    /**
     * getStack  
     * 
     * @param mixed $name 
     * @access private
     * @return void
     */
    private function getStack($name, array $params)
    {
        $result = array();

        if (isset($this->_interceptors[$name])) {
            $stack = $this->_interceptors[$name];

            if (is_string($stack)) {
                $result[] = $this->prepareInterceptor(new $stack(), $params);
            } else if (is_array($stack)) {
                if (isset($stack['interceptor'])) {
                    $class = $stack['interceptor'];
                    $params = isset($stack['params']) ? 
                        array_merge($stack['params'], $params) : $params;
                    $result[] = $this->prepareInterceptor(new $class(), $params);
                } else {
                    foreach ($stack as $key => $val) {
                        if (is_string($key) && is_array($val)) {
                            $params = array_merge($val, $this->getParams($key, $params));
                            $result = array_merge($result, $this->getStack($key, $params));
                        } else {
                            $result = array_merge($result, 
                                $this->getStack($val, $this->getParams($val, $params)));
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * pushInterceptor
     * 
     * @param mixed $name 
     * @param array $params 
     * @access public
     * @return void
     */
    public function push($name, array $params)
    {
        $this->_stack = array_merge($this->_stack, $this->getStack($name, $params));
    }

    /**
     * fetch  
     * 
     * @access public
     * @return void
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

