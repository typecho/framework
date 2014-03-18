<?php

namespace TE\Mvc\Server;

/**
 * AbstractRequest 
 * 
 * @uses RequestInterface
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * 参数列表 
     * 
     * @var array
     * @access private
     */
    private $_params = array();

    /**
     * setParams  
     * 
     * @param array $params 
     * @static
     * @access public
     * @return void
     */
    public function setParams(array $params)
    {
        $this->_params = array_merge($this->_params, $params);
    }


    /**
     * getArg
     *
     * @param string $name
     * @access public
     * @return array
     */
    abstract public function getArg($name);

    /**
     * 获取前端传递参数
     * 
     * @param string $key 参数值 
     * @param mixed $default 
     * @access public
     * @return mixed
     */
    public function get($key, $default = NULL)
    {
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }

        $arg = $this->getArg($key);
        if (false === $arg) {
            return $default;
        }

        return $arg;
    }

    /**
     * 获取数组化的参数
     * 
     * @param mixed $key 
     * @access public
     * @return array
     */
    public function getArray($key)
    {
        if (is_array($key)) {
            $result = array();
            foreach ($key as $k) {
                $val = $this->get($k, NULL);
                $result[$k] = $val;
            }
            return $result;
        } else {
            $result = $this->get($key, array());
            return is_array($result) ? $result : array($result);
        }
    }

    /**
     * 判断复杂的参数情况 
     * 
     * @param mixed $query 前端传递的参数 
     * @access public
     * @return boolean
     */
    public function is($query)
    {
        $validated = false;

        /** 解析串 */
        if (is_string($query)) {
            parse_str($query, $params);
        } else if (is_array($query)) {
            $params = $query;
        }

        /** 验证串 */
        if (!empty($params)) {
            $validated = true;
            foreach ($params as $key => $val) {
                $validated = empty($val) ? ($val != $this->get($key)) : ($val == $this->get($key));

                if (!$validated) {
                    break;
                }
            }
        }

        return $validated;
    }
}

