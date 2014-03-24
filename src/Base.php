<?php

namespace TE;

/**
 * Base 
 * 
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class Base
{
    /**
     * 已经注入的对象池
     * 
     * @var array
     * @access private
     */
    private static $_injectedObjectsPool = array();

    /**
     * 挂起的注入对象栈, 用于解决交叉依赖的问题
     * 
     * @var array
     * @access private
     */
    private static $_holdingObjectsStack = array();

    /**
     * 已经注入的属性
     *
     * @var array
     */
    private $_injectedProperties = array();

    /**
     * __construct  
     * 
     * @access public
     */
    public function __construct()
    {
        // 使用链式注入初始化对象
        $this->initChainedClass();

        // 将init作为实际的初始化方法, __construct方法保留
        if (method_exists($this, 'init')) {
            $args = func_get_args();
            call_user_func_array(array($this, 'init'), $args);
        }
    }

    /**
     * 链式注入
     * 
     * @access private
     * @return void
     */
    private function initChainedClass()
    {
        $class = new \ReflectionClass($this);

        do {
            $this->injectProperties($class);
            $class = $class->getParentClass();
        } while (!empty($class) && 'TE\Mvc\Base' != $class->getName());
    }

    /**
     * 根据给出类获取可以注入的属性列表
     *
     * @param \ReflectionClass  $class      可以是对象也可以是类名
     * @throws \Exception
     */
    private function injectProperties(\ReflectionClass $class)
    {
        $props = $this->getAvailableProperties($class);

        // 检查属性是否可以注入
        foreach ($props as $name => $prop) {
            $shared = Settings::inject($name);
            if (!empty($shared)) {
                // 首先检测交叉依赖
                if (in_array($name, self::$_holdingObjectsStack)) {
                    throw new \Exception('Cross dependencies found in ' . $name);
                }

                // 写入对象池
                if (!isset(self::$_injectedObjectsPool[$name])) {
                    self::$_holdingObjectsStack[] = $name;
                    self::$_injectedObjectsPool[$name] = $this->createInstance($shared);
                    self::$_holdingObjectsStack = array_slice(self::$_holdingObjectsStack, 0, -1);
                }

                $prop->setAccessible(true);
                $prop->setValue($this, self::$_injectedObjectsPool[$name]);
            }
        }
    }

    /**
     * getAvailableProperties  
     * 
     * @param \ReflectionClass $class
     * @access private
     * @return array
     */
    private function getAvailableProperties(\ReflectionClass $class)
    {
        $props = $class->getProperties();
        $result = array();

        foreach ($props as $prop) {
            $name = $prop->getName();
            if ($prop->isDefault() && 0 !== strpos($name, '_')
                && (!isset($this->_injectedProperties[$name]) || $prop->isPrivate())) {
                $result[$name] = $prop;
                $this->_injectedProperties[$name] = true;
            }
        }

        return $result;
    }

    /**
     * 根据定义创建实例 
     * 
     * @param mixed $define 
     * @access private
     * @return Object
     */
    private function createInstance($define)
    {
        if (is_array($define)) {
            $className = $define[0];
            $args = isset($define[1]) ? (is_array($define[1]) ? $define[1] : array($define[1])) : array();

            $reflect = new \ReflectionClass($className);
            return $reflect->newInstanceArgs($args);
        } else if (is_string($define)) {
            return new $define();
        } else if (is_callable($define)) {
            return $define($this);
        }

        return false;
    }
}

