<?php

namespace TE\Helper;

/**
 * Autoloader  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Autoloader
{
    /**
     * registerAutoloader  
     * 
     * @param mixed $path 
     * @param mixed $namespace 
     * @static
     * @access public
     * @return void
     */
    public static function registerNamespace($path, $namespace = NULL)
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
            @include($file);
        });
    }
}

