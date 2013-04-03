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
     * json参数列表
     * 
     * @var array
     * @access private
     */
    private $_jsonParams = array();

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
     * 获取前端传递参数
     * 
     * @param string $key 参数值 
     * @param mixed $default 
     * @param mixed $realKey
     * @access public
     * @return mixed
     */
    public function get($key, $default = NULL, &$realKey = NULL)
    {
        $realKey = $key;
        if (!isset($this->_params[$key])) {
            $request = array_merge($this->getArgs(), $this->_params);
            $paramKeys = explode('|', $key);
            $this->_params[$key] = $default;
            
            foreach ($paramKeys as $paramKey) {
                $filters = NULL;
                
                if (false !== strpos($paramKey, '#')) {
                    list ($paramKey, $filters) = explode('#', $paramKey);
                    $realKey = $paramKey;
                    $filters = explode(',', $filters);
                }

                if (!empty($request[$paramKey]) || (isset($request[$paramKey]) && strlen($request[$paramKey]) > 0)) {
                    $this->_params[$key] = $request[$paramKey];
                    $realKey = $paramKey;
                } else if (false !== strpos($paramKey, ':')) {
                    list($jsonParamKey, $jsonKey) = explode(':', $paramKey);
                    $realKey = $paramKey;
                    if (false !== strpos($jsonKey, '#')) {
                        list ($jsonKey, $filters) = explode('#', $jsonKey);
                        $realKey = $jsonParamKey . ':' . $jsonKey;
                        $filters = explode(',', $filters);
                    }

                    $json = $this->getJson($jsonParamKey);

                    if (isset($json[$jsonKey])) {
                        $this->_params[$key] = $json[$jsonKey];
                    }
                }
                
                if (!empty($filters)) {
                    foreach ($filters as $filter) {
                        $this->_params[$key] = $filter($this->_params[$key]);
                    }
                }
            }
        }

        return $this->_params[$key];
    }

    /**
     * 从请求中获取json数据
     * 
     * @param mixed $key 
     * @access public
     * @return mixed
     */
    public function getJson($key)
    {
        if (!isset($this->_jsonParams[$key])) {
            $this->_jsonParams[$key] = NULL;
            
            if (!empty($_REQUEST[$key])) {
                $result = json_decode($this->get($key), true);
                if (NULL !== $result) {
                    $this->_jsonParams[$key] = $result;
                }
            }
        }

        return $this->_jsonParams[$key];
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
                $val = $this->get($k, NULL, $realKey);
                $result[$realKey] = $val;
            }
            return $result;
        } else {
            $result = $this->get($key, array());
            return is_array($result) ? $result : (NULL === $result ? array() : array($result));
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
        if ($params) {
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

