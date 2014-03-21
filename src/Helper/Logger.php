<?php

namespace TE\Helper;

/**
 * Logger
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Logger
{
    /**
     * _dir  
     * 
     * @var mixed
     * @access private
     */
    private $_dir;

    /**
     * 以某个目录作为根节点
     *
     * @param $dir 目录路径
     */
    public function __construct($dir)
    {
        $this->_dir = $dir;
    }

    /**
     * __call  
     * 
     * @param mixed $name 
     * @param array $args 
     * @access public
     * @return void
     */
    public function __call($name, array $args)
    {
        $message = $args[0];
        $log = date('c') . ' - ';

        if (is_array($message)) {
            foreach ($message as $key => $val) {
                $log .= ' [' . $key . ':' . $val . ']';
            }
        } else {
            $log .= ' ' . $message;
        }

        error_log($log . "\n", 3, $this->_dir . '/' . $name . '.log');
    }
}

