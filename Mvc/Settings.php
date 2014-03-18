<?php

namespace TE\Mvc;

use TE\Mvc\Action\Interceptor\InterceptorManager;

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
     * @param Settings $class
     */
    public final static function setClass(Settings $class)
    {
        self::$_class = $class;
    }

    /**
     * @param $name
     * @param array $args
     * @return mixed
     */
    public final static function __callStatic($name, array $args)
    {
        if (empty(self::$_class)) {
            self::$_class = new self;
        }

        return call_user_func_array(array(self::$_class, $name), $args);
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function inject($name)
    {
        return NULL;
    }

    /**
     * @param string $resultName
     * @return string
     */
    protected function getResultClass($resultName)
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
     * @param $resultName
     * @param $params
     * @return array
     */
    protected function getResultParams($resultName, $params)
    {
        return $params;
    }

    /**
     * @param InterceptorManager $interceptorManager
     * @param $name
     * @return InterceptorManager
     */
    protected function pushInterceptor(InterceptorManager $interceptorManager, $name)
    {
        $interceptorManager->push($name);
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function routerNotFound()
    {
        throw new \Exception('Please configure routerNotFound settings');
    }

    /**
     * @param \Exception $e
     */
    protected function catchException(\Exception $e)
    {
        echo '<pre>' . nl2br((string) $e) . '</pre>';
        exit;
    }
}
