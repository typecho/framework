<?php

namespace TE\Mvc\Data;

use TE\Base;

/**
 * ResultData
 * 
 * @uses Base
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractData extends Base implements \Iterator, \Countable, \ArrayAccess
{
    /**
     * 原始数据
     *
     * @var array
     */
    private $_originalData = array();

    /**
     * _data  
     * 
     * @var array
     * @access private
     */
    private $_data = array();

    /**
     * _pos  
     * 
     * @var integer
     * @access private
     */
    private $_pos = 0;

    /**
     * _length  
     * 
     * @var float
     * @access private
     */
    private $_length = 0;

    /**
     * _multi  
     * 
     * @var mixed
     * @access private
     */
    private $_multi = false;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        if (empty($data)) {
            return;
        }
        
        $this->_multi = is_array(current($data)) && is_int(key($data));
        $this->_originalData = $data;

        if ($this->_multi) {
            $this->_data = $data;
        } else {
            $this->_data[] = $data;
        }

        $this->_length = count($this->_data);

        parent::__construct();
        $this->_data = array_map(array($this, 'prepare'), $this->_data);
    }

    /**
     * getData
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * getOriginalData
     *
     * @return array
     */
    public function getOriginalData()
    {
        return $this->_originalData;
    }
    
    /**
     * prepare  
     * 
     * @param array $row 
     * @access protected
     * @return array
     */
    protected function prepare(array $row)
    {
        return $row;
    }

    /**
     * fallback  
     * 
     * @param mixed $name 
     * @access protected
     * @return mixed
     */
    protected function fallback($name)
    {
        return NULL;
    }

    /**
     * isMulti  
     * 
     * @access public
     * @return boolean
     */
    public function isMulti()
    {
        return $this->_multi;
    }

    /**
     * __set  
     * 
     * @param mixed $name 
     * @param mixed $value 
     * @access public
     * @return void
     */
    public function __set($name, $value)
    {}

    /**
     * __get 
     * 
     * @param mixed $name 
     * @access public
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (!$this->_length) {
            throw new \Exception('Can not access null data');
        }

        $method = 'get' . ucfirst($name);
        $key = trim(preg_replace_callback("/([A-Z])/", function($matches){
            return '_' . strtolower($matches[1]);
        } , $name), '_');
        if (array_key_exists($name, $this->_data[$this->_pos])) {
            return $this->_data[$this->_pos][$name];
        } else if (array_key_exists($key, $this->_data[$this->_pos])) {
            return $this->_data[$this->_pos][$key];
        } else if (method_exists($this, $method)) {
            $this->_data[$this->_pos][$name] = $this->{$method}();
            return $this->_data[$this->_pos][$name];
        }

        return $this->fallback($name);
    }

    /**
     * __call  
     * 
     * @param mixed $name 
     * @param mixed $args 
     * @access public
     * @return void
     * @throws \Exception
     */
    public function __call($name, $args)
    {
        if (!$this->_length) {
            throw new \Exception('Can not access null data');
        }

        if (empty($args)) {
            echo $this->{$name};
        } else {
            $method = 'get' . ucfirst($name);
            echo call_user_func_array(array($this, $method), $args);
        }
    }

    /**
     * rewind  
     * 
     * @access public
     * @return void
     */
    public function rewind()
    {
        $this->_pos = 0;
    }

    /**
     * current  
     * 
     * @access public
     * @return AbstractData
     */
    public function current()
    {
        return $this;
    }

    /**
     * row  
     * 
     * @param mixed $key 
     * @access public
     * @return array
     */
    public function row($key = NULL)
    {
        return $key ? $this->_data[$this->_pos][$key] : $this->_data[$this->_pos];
    }

    /**
     * key  
     * 
     * @access public
     * @return integer
     */
    public function key()
    {
        return $this->_pos;
    }

    /**
     * next  
     * 
     * @access public
     * @return void
     */
    public function next()
    {
        $this->_pos ++;
    }

    /**
     * valid  
     * 
     * @access public
     * @return boolean
     */
    public function valid()
    {
        return isset($this->_data[$this->_pos]);
    }

    /**
     * count  
     * 
     * @access public
     * @return integer
     */
    public function count()
    {
        return $this->_length;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     *                      An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     *       The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->_originalData[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return isset($this->_originalData[$offset]) ? $this->_originalData[$offset] : NULL;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     * </p>
     * @param mixed $value  <p>
     *                      The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->_originalData[] = $value;
        } else {
            $this->_originalData[$offset] = $value;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->_originalData[$offset]);
    }


    /**
     * last 
     * 
     * @param string $foo 
     * @param string $bar 
     * @access public
     * @return boolean
     */
    public function last($foo = '', $bar = '')
    {
        if ($this->_pos == $this->_length - 1) {
            echo $bar;
            return true;
        }

        echo $foo;
        return false;
    }

    /**
     * 根据余数输出
     *
     * @access public
     * @return void
     */
    public function alt()
    {
        $args = func_get_args();
        $num = func_num_args();
        $split = $this->_pos % $num;
        echo $args[(0 == $split ? $num : $split) -1];
    }

    /**
     * 格式化解析堆栈内的所有数据
     *
     * @param string $format 数据格式
     * @return void
     */
    public function parse($format)
    {
        foreach ($this as $val) {
            preg_replace_callback("/\{([_a-z0-9\.]+)\}/i", function ($matches) use ($val) {
                $parts = explode('.', $matches[1]);

                foreach ($parts as $part) {
                    $val = $val->{$part};
                }

                return $val;
            }, $format);
        }
        
        $this->_pos = 0;
    }

    /**
     * toArray
     * 
     * @param mixed $fields 
     * @access public
     * @return array
     */
    public function toArray($fields = NULL)
    {
        $result = array();
        $lastPost = $this->_pos;
        
        if (empty($fields)) {
            $result = $this->_data;
        } else {
            $this->_pos = 0;

            foreach ($this as $val) {
                if (is_string($fields)) {
                    if (strpos($fields, ':')) {
                        $args = explode(':', $fields);
                        $method = 'get' . array_shift($args);

                        $result[] = call_user_func_array(array($val, $method), $args);
                    } else {
                        $result[] = $val->{$fields};
                    }
                } else if (is_array($fields)) {
                    $row = array();

                    foreach ($fields as $key => $field) {
                        if (!is_int($key)) {
                            $row[$key] = $val->{$key} ? $val->{$key}->toArray($field) : NULL;
                        } else {
                            if (strpos($field, ':')) {
                                $args = explode(':', $field);
                                $method = 'get' . ucfirst(array_shift($args));

                                $row[$field] = call_user_func_array(array($val, $method), $args);
                            } else {
                                $row[$field] = $val->{$field};
                            }
                        }
                    }

                    $result[] = $row;
                }
            }
            
            $this->_pos = $lastPost;
        }

        return $this->_multi ? $result : array_pop($result);
    }
}

