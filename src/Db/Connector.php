<?php

namespace TE\Db;

use TE\Db\Query\Delete;
use TE\Db\Query\Insert;
use TE\Db\Adapter\AdapterException;
use TE\Db\Adapter\AdapterInterface;
use TE\Db\Query\Select;
use TE\Db\Query\Update;
use TE\Db\Query\Query;

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
     * @param string  $adapterName   adapter name
     * @param array  $params        db connect params
     * @param string $prefix        the prefix of table
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
     * @param string $table
     * @param mixed $columns
     * @access public
     * @return Select
     */
    public function select($table, $columns = NULL)
    {
        return new Select($this->_adapter, $this->_prefix, $table, $columns);
    }

    /**
     * update  
     * 
     * @param string $table
     * @access public
     * @return Update
     */
    public function update($table)
    {
        return new Update($this->_adapter, $this->_prefix, $table);
    }

    /**
     * delete  
     * 
     * @param string $table
     * @access public
     * @return Delete
     */
    public function delete($table)
    {
        return new Delete($this->_adapter, $this->_prefix, $table);
    }

    /**
     * insert  
     * 
     * @param string $table
     * @access public
     * @return Insert
     */
    public function insert($table)
    {
        return new Insert($this->_adapter, $this->_prefix, $table);
    }

    /**
     * query
     * 
     * @param string $query
     * @access public
     * @return Query
     */
    public function query($query)
    {
        return new Query($this->_adapter, $this->_prefix, $query);
    }

    /**
     * table
     *
     * @param string $table
     * @return Table
     */
    public function table($table)
    {
        return new Table($table, $this);
    }
}

