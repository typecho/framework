<?php

namespace TE\Mvc;

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
     * _injectiveObjects 
     * 
     * @var array
     * @access private
     */
    private static $_injectiveObjects = array();

    /**
     * __construct  
     * 
     * @access public
     * @return void
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
     * setInjectiveObjects
     * 
     * @param array $objects 
     * @static
     * @access public
     * @return void
     */
    public static function setInjectiveObjects(array $objects)
    {
        self::$_injectiveObjects = array_merge(self::$_injectiveObjects, $objects);
    }

    /**
     * 链式注入
     * 
     * @access private
     * @return void
     */
    private function initChainedClass()
    {
        $class = $this;
        $shared = self::$_injectiveObjects;
        
        do {
            $this->injectProperties($class, $shared);
            $class = get_parent_class($class);
        } while ('TE\Mvc\Base' != ltrim($class, '\\') && !empty($class));
    }

    /**
     * 根据给出类获取可以注入的属性列表 
     * 
     * @param mixed $class 可以是对象也可以是类名
     * @param array $shared 
     * @access private
     * @return void
     */
    private function injectProperties($class, array $shared)
    {
        $props = $this->getAvailableProperties($class);
        $result = array();

        // 检查属性是否可以注入
        foreach ($props as $prop) {
            $setter = 'set' . ucfirst($prop);

            if (isset($shared[$prop]) && method_exists($this, $setter)) {
                // 首先检测交叉依赖
                if (in_array($prop, self::$_holdingObjectsStack)) {
                    throw new \Exception('Cross dependencies found in ' . $prop);
                }

                // 写入对象池
                if (!isset(self::$_injectedObjectsPool[$prop])) {
                    array_push(self::$_holdingObjectsStack, $prop);
                    self::$_injectedObjectsPool[$prop] = $this->createInstance($shared[$prop]);
                    array_pop(self::$_holdingObjectsStack);
                }
                
                $this->{$setter}(self::$_injectedObjectsPool[$prop]);
            }
        }
    }

    /**
     * getAvailableProperties  
     * 
     * @param mixed $class 
     * @access private
     * @return void
     */
    private function getAvailableProperties($class)
    {
        $reflect = new \ReflectionClass($class);
        $props = $reflect->getProperties(is_object($class) ? \ReflectionProperty::IS_PUBLIC 
            | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE
            : \ReflectionProperty::IS_PRIVATE);
        $result = array();

        foreach ($props as $prop) {
            $name = $prop->getName();
            if ($prop->isDefault() && 0 !== strpos($name, '_')) {
                $result[] = $name;
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

