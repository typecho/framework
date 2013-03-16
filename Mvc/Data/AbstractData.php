<?php

namespace TE\Mvc\Data;

use TE\Mvc\Base;

/**
 * ViewData 
 * 
 * @uses Base
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractData extends Base implements \Iterator, \Countable
{
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
     * __construct  
     * 
     * @param mixed $data
     * @access public
     * @return void
     */
    public function __construct($data)
    {
        if (empty($data)) {
            return;
        }
        
        $this->_multi = is_array(current($data)) && is_int(key($data));

        if ($this->_multi) {
            $this->_data = $data;
        } else {
            $this->_data[] = $data;
        }

        $this->_data = array_map(array($this, 'prepare'), $this->_data);
        $this->_length = count($this->_data);
        parent::__construct();
    }
    
    /**
     * prepare  
     * 
     * @param array $row 
     * @access protected
     * @return void
     */
    protected function prepare(array $row)
    {
        return $row;
    }

    /**
     * isMulti  
     * 
     * @access public
     * @return void
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
     * @return void
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        $key = dash_name($name);
        if (array_key_exists($name, $this->_data[$this->_pos])) {
            return $this->_data[$this->_pos][$name];
        } else if (array_key_exists($key, $this->_data[$this->_pos])) {
            return $this->_data[$this->_pos][$key];
        } else if (method_exists($this, $method)) {
            $this->_data[$this->_pos][$key] = $this->{$method}();
            return $this->_data[$this->_pos][$key];
        } else {
            return parent::__get($name);
        }
    }

    /**
     * __call  
     * 
     * @param mixed $name 
     * @param mixed $args 
     * @access public
     * @return void
     */
    public function __call($name, $args)
    {
        if (empty($args)) {
            echo $this->{$name};
        } else {
            $method = 'get' . $name;
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
     * @return void
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
     * @return void
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
     * @return void
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
     * last 
     * 
     * @param string $foo 
     * @param string $bar 
     * @access public
     * @return void
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
     * @param string $param 需要输出的值
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
     * @return void
     */
    public function toArray($fields = NULL)
    {
        $result = array();
        
        if (empty($fields)) {
            $result = $this->_data;
        } else {
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
                                $field = array_shift($args);
                                $method = 'get' . $field;

                                $row[$field] = call_user_func_array(array($val, $method), $args);
                            } else {
                                $row[$field] = $val->{$field};
                            }
                        }
                    }

                    $result[] = $row;
                }
            }
            
            $this->_pos = 0;
        }

        return $this->_multi ? $result : array_pop($result);
    }
}

