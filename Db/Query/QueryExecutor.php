<?php

namespace TE\Db\Query;

use TE\Db\Adapter\AdapterInterface as Adapter;

/**
 * QueryExecutor  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class QueryExecutor
{
    /**
     * _adapter  
     * 
     * @var mixed
     * @access private
     */
    private $_adapter;

    /**
     * _query  
     * 
     * @var mixed
     * @access private
     */
    private $_query;

    /**
     * _name  
     * 
     * @var mixed
     * @access private
     */
    private $_name;

    /**
     * _prefix  
     * 
     * @var mixed
     * @access private
     */
    private $_prefix;

    /**
     * __construct 
     * 
     * @param Adapter $adapter 
     * @param mixed $prefix 
     * @access public
     * @return void
     */
    public function __construct(Adapter $adapter, $prefix)
    {
        $this->_adapter = $adapter;
        $this->_prefix = $prefix;
    }

    /**
     * __call  
     * 
     * @param mixed $name 
     * @param mixed $args 
     * @access public
     * @return void
     */
    public function __call($name, $args)
    {
        switch($name) {
            case 'select':
            case 'update':
            case 'insert':
            case 'delete':
            case 'query':
                if (empty($this->_query)) {
                    $this->_name = $name;
                    $reflect = new \ReflectionClass('TE\Db\Query\\' . ucfirst($name));
                    array_unshift($args, $this->_prefix);
                    $this->_query = $reflect->newInstanceArgs($args);
                }
                break;
            default:
                if (!empty($this->_query)) {
                    if (method_exists($this->_query, $name)) {
                        call_user_func_array(array($this->_query, $name), $args);
                    }
                }
                break;
        }

        return $this;
    }

    /**
     * __toString  
     * 
     * @access public
     * @return void
     */
    public function __toString()
    {
        switch ($this->_name) {
            case 'select':
                return $this->_adapter->parseSelect($this->_query);
            case 'update':
                return $this->_adapter->parseUpdate($this->_query);
            case 'insert':
                return $this->_adapter->parseInsert($this->_query);
            case 'delete':
                return $this->_adapter->parseDelete($this->_query);
            case 'query':
                return $this->_adapter->parseQuery($this->_query);
            default:
                return;
        }
    }

    /**
     * fetchOne  
     * 
     * @param mixed $column 
     * @access public
     * @return void
     */
    public function fetchOne($column = NULL)
    {
        if ('select' == $this->_name) {
            $handle = $this->_adapter->query((string) $this);
            $result = $this->_adapter->fetchOne($handle);
            return empty($column) ? $result : $result[$column];
        }
    }

    /**
     * fetchAll  
     * 
     * @access public
     * @return void
     */
    public function fetchAll()
    {
        if ('select' == $this->_name) {
            $handle = $this->_adapter->query((string) $this);
            return $this->_adapter->fetchAll($handle);
        }
    }

    /**
     * exec
     * 
     * @access public
     * @return void
     */
    public function exec()
    {
        switch ($this->_name) {
            case 'update':
            case 'delete':
            case 'insert':
            case 'query':
                $handle = $this->_adapter->query((string) $this);
                
                if ('update' == $this->_name || 'delete' == $this->_name) {
                    return $this->_adapter->affectedRows($handle);
                } else if ('insert' == $this->_name) {
                    return $this->_adapter->lastInsertId($handle);
                }
                return;
            default:
                return;
        }
    }
}

