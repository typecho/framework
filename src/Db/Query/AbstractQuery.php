<?php

namespace TE\Db\Query;

use TE\Db\Adapter\AdapterInterface;

/**
 * AbstractQuery
 *
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com>
 * @license GNU General protected License 2.0
 */
abstract class AbstractQuery
{
    /**
     * _query  
     * 
     * @var array
     * @access private
     */
    private $_query = array();

    /**
     * _prefix  
     * 
     * @var string
     * @access private
     */
    private $_prefix;

    /**
     * 适配器
     *
     * @var AdapterInterface
     */
    private $_adapter;

    /**
     * 名称
     *
     * @var string
     */
    private $_name = '';

    /**
     * @param AdapterInterface $adapter
     * @param string           $prefix
     */
    public function __construct(AdapterInterface $adapter, $prefix)
    {
        $this->_adapter = $adapter;
        $this->_prefix = $prefix;

        $className = explode("\\", get_class($this));
        $this->_name = strtolower(array_pop($className));

        if (method_exists($this, 'init')) {
            $args = func_get_args();
            $args = array_slice($args, 2);
            call_user_func_array(array($this, 'init'), $args);
        }
    }
    
    /**
     * applyPrefix  
     * 
     * @param mixed $value 
     * @access protected
     * @return string
     */
    protected function applyPrefix($value)
    {
        if (is_array($value)) {
            $result = array();

            foreach ($value as $key => $val) {
                $result[$this->applyPrefix($key)] = $val;
            }

            return $result;
        } else if (is_string($value)) {
            return str_replace('@', $this->_prefix, $value);
        }

        return $value;
    }

    /**
     * setQuery  
     * 
     * @param mixed $name 
     * @param mixed $value 
     * @access protected
     * @return void
     */
    protected function setQuery($name, $value)
    {
        $this->_query[$name] = $value;
    }

    /**
     * pushQuery  
     * 
     * @param mixed $name 
     * @param mixed $value 
     * @access protected
     * @return void
     */
    protected function pushQuery($name, $value)
    {
        if (!isset($this->_query[$name])) {
            $this->_query[$name] = array();
        }

        $this->_query[$name][] = $value;
    }

    /**
     * getQuery  
     * 
     * @param mixed $name 
     * @access public
     * @return mixed
     */
    public function getQuery($name)
    {
        return isset($this->_query[$name]) ? $this->_query[$name] : NULL;
    }

    /**
     * where  
     *
     * @access public
     * @return $this
     */
    public function where()
    {
        $args = func_get_args();
        if (!empty($args)) {
            $this->pushQuery('where', array('AND', $args));
        }

        return $this;
    }

    /**
     * orWhere  
     *
     * @access public
     * @return $this
     */
    public function orWhere()
    {
        $args = func_get_args();
        if (!empty($args)) {
            $this->pushQuery('where', array('OR', $args));
        }

        return $this;
    }

    /**
     * getAdapter
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * __toString
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        $method = 'parse' . ucfirst($this->_name);
        return $this->_adapter->{$method}($this);
    }
}

