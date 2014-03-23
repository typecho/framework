<?php

namespace TE;

use TE\Mvc\Controller\Interceptor\InterceptorManager;
use TE\Mvc\Result\AbstractResult;

/**
 * Class Settings
 * @package TE\Mvc
 */
class Settings
{
    /**
     * @var Settings
     */
    private static $_class;

    /**
     * @return mixed
     */
    final private static function call()
    {
        if (empty(self::$_class)) {
            self::$_class = new self;
        }

        $args = func_get_args();
        $name = 'on' . ucfirst(array_shift($args));

        return call_user_func_array(array(self::$_class, $name), $args);
    }

    /**
     * @param string $class
     */
    final public static function setClass($class)
    {
        if (is_subclass_of($class, '\TE\Settings')) {
            self::$_class = new $class;
        }
    }

    /**
     * autoLoad 
     * 
     * @param mixed $path 
     * @param mixed $namespace 
     */
    final public static function autoLoad($path, $namespace = NULL)
    {
        spl_autoload_register(function ($class) use ($path, $namespace) {
            if (!empty($namespace)) {
                if (0 == strpos(ltrim($class, '\\'), $namespace . '\\')) {
                    $class = substr(ltrim($class, '\\'), strlen($namespace) + 1);
                } else {
                    return;
                }
            }

            $file = $path . '/' . str_replace(array('_', '\\'), '/', $class) . '.php';
            if (file_exists($file)) {
                include_once $file;
            }
        });
    }

    
    /**
     * @param $name
     * @return mixed
     */
    final public static function inject($name)
    {
        return self::call(__FUNCTION__, $name);
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function onInject($name)
    {
        return NULL;
    }

    /**
     * @param string $resultName
     * @return string
     */
    final public static function getResultClass($resultName)
    {
        return self::call(__FUNCTION__, $resultName);
    }

    /**
     * @param string $resultName
     * @return string
     */
    protected function onGetResultClass($resultName)
    {
        static $defaultResultClasses;

        if (empty($defaultResultClasses)) {
            $defaultResultClasses = array(
                'back'              =>  'TE\Mvc\Result\Back',
                'blank'             =>  'TE\Mvc\Result\Blank',
                'content'           =>  'TE\Mvc\Result\Content',
                'error'             =>  'TE\Mvc\Result\Error',
                'json'              =>  'TE\Mvc\Result\Json',
                'jsonp'             =>  'TE\Mvc\Result\Jsonp',
                'notFound'          =>  'TE\Mvc\Result\NotFound',
                'redirect'          =>  'TE\Mvc\Result\Redirect',
                'template'          =>  'TE\Mvc\Result\Template'
            );
        }

        return isset($defaultResultClasses[$resultName]) ?
            $defaultResultClasses[$resultName] : $resultName;
    }

    /**
     * @param string $resultName
     * @param AbstractResult $result
     */
    final public static function setResult($resultName, AbstractResult $result)
    {
        self::call(__FUNCTION__, $resultName, $result);
    }

    /**
     * @param string $resultName
     * @param AbstractResult $result
     */
    protected function onSetResult($resultName, AbstractResult $result)
    {}

    /**
     * @param InterceptorManager $interceptorManager
     * @param $name
     * @return InterceptorManager
     */
    final public static function pushInterceptor(InterceptorManager $interceptorManager, $name)
    {
        return self::call(__FUNCTION__, $interceptorManager, $name);
    }

    /**
     * @param InterceptorManager $interceptorManager
     * @param $name
     * @return InterceptorManager
     */
    protected function onPushInterceptor(InterceptorManager $interceptorManager, $name)
    {
        $interceptorManager->push($name);
    }

    /**
     * @return string
     * @throws \Exception
     */
    final public static function routerNotFound()
    {
        return self::call(__FUNCTION__);
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function onRouterNotFound()
    {
        throw new \Exception('Please configure routerNotFound settings');
    }

    /**
     * @param \Exception $e
     */
    final public static function catchException(\Exception $e)
    {
        self::call(__FUNCTION__, $e);
    }

    /**
     * @param \Exception $e
     */
    protected function onCatchException(\Exception $e)
    {
        echo '<pre>' . ((string) $e) . '</pre>';
        exit;
    }
}
