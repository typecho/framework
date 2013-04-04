<?php

namespace TE\Helper;

/**
 * Config  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Config
{
    /**
     * _dir  
     * 
     * @var mixed
     * @access private
     */
    private $_dir;

    /**
     * _config
     * 
     * @var mixed
     * @access private
     */
    private $_config;

    /**
     * @param $dir
     */
    public function __construct($dir)
    {
        $this->_dir = $dir;
    }

    /**
     * 获取配置的魔术方法 
     * 
     * @param string $name 
     * @access public
     * @return mixed
     */
    public function __get($name)
    {
        if (!isset($this->_config[$name])) {
            $prefix = $this->_dir . '/' . $name;

            if (is_dir($prefix)) {
                $this->_config[$name] = new Config($prefix);
            } else if (file_exists($prefix . '.php')) {
                $this->_config[$name] = require($prefix . '.php');
            } else {
                $this->_config[$name] = NULL;
            }
        }

        return $this->_config[$name];
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
    {
        $this->_config[$name] = $value;
    }
}

