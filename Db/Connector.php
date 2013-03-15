<?php

namespace TE\Db;

use TE\Db\Query\QueryExecutor;
use TE\Db\Adapter\AdapterException;

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
     * @var QueryExecutor
     * @access private
     */
    private $_executor;

    /**
     * __construct 
     * 
     * @param mixed $adapterName 
     * @param array $params 
     * @param string $prefix 
     * @access public
     * @return void
     */
    public function __construct($adapterName, array $params, $prefix = '')
    {
        if (!isset($this->_adapters[$adapterName])) {
            throw new AdapterException("Adapter '{$adapterName}' not found");
        }

        $adapterReflect = new \ReflectionClass($this->_adapters[$adapterName]);
        $adapter = $adapterReflect->newInstanceArgs($params);

        $this->_executor = new QueryExecutor($adapter, $prefix);
    }

    /**
     * select  
     * 
     * @param mixed $table 
     * @param array $columns 
     * @access public
     * @return void
     */
    public function select($table, array $columns = array())
    {
        return $this->_executor->select($table, $columns);
    }

    /**
     * update  
     * 
     * @param mixed $table 
     * @access public
     * @return void
     */
    public function update($table)
    {
        return $this->_executor->update($table);
    }

    /**
     * delete  
     * 
     * @param mixed $table 
     * @access public
     * @return void
     */
    public function delete($table)
    {
        return $this->_executor->delete($table);
    }

    /**
     * insert  
     * 
     * @param mixed $table 
     * @access public
     * @return void
     */
    public function insert($table)
    {
        return $this->_executor->insert($table);
    }
}

