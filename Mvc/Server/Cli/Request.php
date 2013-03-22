<?php

namespace TE\Mvc\Server\Cli;

use TE\Mvc\Server\AbstractRequest;

/**
 * Request  
 * 
 * @uses AbstractRequest
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Request extends AbstractRequest
{
    /**
     * _args  
     * 
     * @var mixed
     * @access private
     */
    private $_args = NULL;

    /**
     * 命令行模式
     * 
     * @var boolean
     * @access private
     */
    private $_isConsole = NULL;

    /**
     * 路径信息
     *
     * @access private
     * @var string
     */
    private $_pathInfo = NULL;

    /**
     * 是否为命令行模式运行
     * 
     * @static
     * @access public
     * @return void
     */
    public function isConsole()
    {
        if (NULL === $this->_isConsole) {
            $this->_isConsole = isset($_SERVER['_']) || isset($_SERVER['COMMAND_MODE']);
        }

        return $this->_isConsole;
    }

    /**
     * getCliArgs  
     * 
     * @static
     * @access public
     * @return void
     */
    public function getArgs()
    {
        if (NULL === $this->_args) {
            global $argv;
            $this->_args = array();

            if (!empty($argv)) {
                foreach ($argv as $arg) {
                    $arg = trim($arg);
                    if (0 === strpos($arg, '--')) {
                        $parts = explode('=', substr($arg, 2), 2);
                        $count = count($parts);

                        if (2 == $count) {
                            list ($key, $value) = $parts;
                            $this->_args[$key] = preg_replace("|^('\")(.*)\\1$|", "\\2", $value);
                        } else if (1 == $count) {
                            list ($key) = $parts;
                            $this->_args[$key] = '';
                        }
                    } else if (0 === strpos($arg, '-')) {
                        $arg = substr($arg, 1);
                        $len = strlen($arg);

                        if ($len > 1) {
                            $key = substr($arg, 0, 1);
                            $value = substr($arg, 1);
                            $this->_args[$key] = preg_replace("|^('\")(.*)\\1$|", "\\2", $value);
                        } else if (1 == $len) {
                            $this->_args[$arg] = '';
                        }
                    } else {
                        $this->_args[$arg] = '';
                    }
                }
            }
        }

        return $this->_args;
    }

    /**
     * getPathInfo  
     * 
     * @access public
     * @return void
     */
    public function getPathInfo()
    {
        /** 缓存信息 */
        if (NULL !== $this->_pathInfo) {
            return $this->_pathInfo;
        }
        
        global $argv;
        $pathInfo = '';

        if (count($argv) > 0) {
            $pathInfo = $argv[0];
        }
        
        return $pathInfo;
    }
}

