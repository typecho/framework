<?php

namespace TE\Db;

use TE\Db\Query\QueryExecutor;
use TE\Db\Adapter\AdapterException;
use TE\Db\Adapter\AdapterInterface;

/**
 * Connector  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Connector
{
    /**
     * _adapters  
     * 
     * @var array
     * @access private
     */
    private $_adapters = array(
        'Mysql'     =>  'TE\Db\Adapter\Mysql',
        'SQLite'    =>  'TE\Db\Adapter\SQLite',
        'PdoMysql'  =>  'TE\Db\Adapter\Pdo\Mysql',
        'PdoSQLite' =>  'TE\Db\Adapter\Pdo\SQLite',
        'PdoPgsql'  =>  'TE\Db\Adapter\Pdo\Pgsql'
    );

    /**
     * _executor  
     * 
     * @var AdapterInterface
     * @access private
     */
    private $_adapter;

    /**
     * _prefix  
     * 
     * @var string
     * @access private
     */
    private $_prefix;

    /**
     * @param        $adapterName   适配器名称
     * @param array  $params        初始化参数
     * @param string $prefix        表名前缀
     * @throws AdapterException
     */
    public function __construct($adapterName, array $params, $prefix = '')
    {
        if (!isset($this->_adapters[$adapterName])) {
            throw new AdapterException("Adapter '{$adapterName}' not found");
        }

        $adapterReflect = new \ReflectionClass($this->_adapters[$adapterName]);
        $this->_adapter = $adapterReflect->newInstanceArgs($params);
        $this->_prefix = $prefix;
    }

    /**
     * select  
     * 
     * @param mixed $table 
     * @param array $columns 
     * @access public
     * @return QueryExecutor
     */
    public function select($table, array $columns = array())
    {
        $executor = new QueryExecutor($this->_adapter, $this->_prefix);
        return $executor->select($table, $columns);
    }

    /**
     * update  
     * 
     * @param mixed $table 
     * @access public
     * @return QueryExecutor
     */
    public function update($table)
    {
        $executor = new QueryExecutor($this->_adapter, $this->_prefix);
        return $executor->update($table);
    }

    /**
     * delete  
     * 
     * @param mixed $table 
     * @access public
     * @return QueryExecutor
     */
    public function delete($table)
    {
        $executor = new QueryExecutor($this->_adapter, $this->_prefix);
        return $executor->delete($table);
    }

    /**
     * insert  
     * 
     * @param mixed $table 
     * @access public
     * @return QueryExecutor
     */
    public function insert($table)
    {
        $executor = new QueryExecutor($this->_adapter, $this->_prefix);
        return $executor->insert($table);
    }

    /**
     * query
     * 
     * @param mixed $query
     * @access public
     * @return QueryExecutor
     */
    public function query($query)
    {
        $executor = new QueryExecutor($this->_adapter, $this->_prefix);
        return $executor->query($query);
    }
}

