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
     * __construct  
     * 
     * @param mixed $dir 
     * @access public
     * @return void
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
     * @return void
     */
    public function __get($name)
    {
        if (!isset($this->_config[$name])) {
            if (is_dir($this->_dir . '/' . $name)) {
                $this->_config[$name] = new Config($this->_dir . '/' . $name);
            } else {
                $this->_config[$name] = require($this->_dir . '/' . $name . '.php');
            }
        }

        return $this->_config[$name];
    }
}

