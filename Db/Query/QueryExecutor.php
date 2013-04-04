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
     * @param Adapter $adapter
     * @param         $prefix
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
     * @return QueryExecutor
     */
    public function __call($name, $args)
    {
        if (!empty($this->_query)) {
            if (method_exists($this->_query, $name)) {
                call_user_func_array(array($this->_query, $name), $args);
            }
        }

        return $this;
    }

    /**
     * select
     *
     * @param       $table
     * @param array $columns
     * @return QueryExecutor
     */
    public function select($table, array $columns = array())
    {
        $this->_name = 'select';
        $this->_query = new Select($this->_prefix, $table, $columns);
        return $this;
    }

    /**
     * update
     *
     * @param $table
     * @return QueryExecutor
     */
    public function update($table)
    {
        $this->_name = 'update';
        $this->_query = new Update($this->_prefix, $table);
        return $this;
    }

    /**
     * insert
     *
     * @param $table
     * @return QueryExecutor
     */
    public function insert($table)
    {
        $this->_name = 'insert';
        $this->_query = new Insert($this->_prefix, $table);
        return $this;
    }

    /**
     * delete
     *
     * @param $table
     * @return QueryExecutor
     */
    public function delete($table)
    {
        $this->_name = 'delete';
        $this->_query = new Delete($this->_prefix, $table);
        return $this;
    }

    /**
     * query
     *
     * @param $query
     * @return QueryExecutor
     */
    public function query($query)
    {
        $this->_name = 'query';
        $this->_query = new Query($this->_prefix, $query);
        return $this;
    }

    /**
     * __toString  
     * 
     * @access public
     * @return string
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
                return '';
        }
    }

    /**
     * fetchOne  
     * 
     * @param mixed $column 
     * @access public
     * @return mixed
     */
    public function fetchOne($column = NULL)
    {
        if ('select' == $this->_name) {
            $handle = $this->_adapter->query((string) $this);
            $result = $this->_adapter->fetchOne($handle);
            return empty($column) ? $result : $result[$column];
        }

        return NULL;
    }

    /**
     * fetchAll  
     * 
     * @access public
     * @return array
     */
    public function fetchAll()
    {
        if ('select' == $this->_name) {
            $handle = $this->_adapter->query((string) $this);
            return $this->_adapter->fetchAll($handle);
        }

        return array();
    }

    /**
     * exec
     * 
     * @access public
     * @return mixed
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
                return NULL;
            default:
                return NULL;
        }
    }
}

