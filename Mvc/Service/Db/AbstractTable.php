<?php

namespace TE\Mvc\Service\Db;

use TE\Db\Query\AbstractQuery;
use TE\Mvc\Base;
use TE\Db\Connector;

/**
 * Class AbstractTable
 *
 * @package TE\Mvc\Service\Db
 */
abstract class AbstractTable extends Base
{
    /**
     * db  
     * 
     * @var Connector
     */
    protected $serviceDb;

    /**
     * _table  
     * 
     * @var string
     */
    private $_table;

    /**
     * _primaryKey  
     * 
     * @var string
     */
    private $_primaryKey;

    /**
     * parseWhere
     *
     * @param AbstractQuery $query
     * @param array $key
     */
    private function parseWhere(AbstractQuery $query, array $key)
    {
        foreach ($key as $index => $where) {
            if (is_int($index) && is_array($where)) {
                list ($column, $op, $value) = $where;
                $query->where("{$column} {$op} ?", $value);
            } else {
                $query->where("{$index} = ?", $where);
            }
        }
    }

    /**
     * parseKey
     *
     * @param AbstractQuery $query
     * @param               $key
     * @throws \Exception
     */
    private function parseKey(AbstractQuery $query, $key)
    {
        $pk = $this->getPrimaryKey();
        if (is_array($pk)) {
            if (!is_array($key) || count($pk) != count($key)) {
                throw new \Exception('Primary key not matched');
            }

            foreach ($pk as $index => $column) {
                $query->where("{$column} = ?", $key[$index]);
            }
        } else {
            $query->where("{$pk} = ?", $key);
        }
    }

    /**
     * parseKeys
     *
     * @param AbstractQuery $query
     * @param array         $keys
     */
    private function parseKeys(AbstractQuery $query, array $keys)
    {
        $pk = $this->getPrimaryKey();
        if (is_array($pk)) {
            $condition = implode(' AND ', array_map(function ($key) {
                return "{$key} = ?";
            }, $pk));

            foreach ($keys as $key) {
                array_unshift($key, $condition);
                call_user_func_array(array($query, 'orWhere'), $key);
            }
        } else {
            $query->where("{$pk} IN ?", $keys);
        }
    }

    /**
     * setServiceDb
     * 
     * @param Connector $serviceDb
     * @access public
     * @return void
     */
    public function setServiceDb(Connector $serviceDb)
    {
        $this->serviceDb = $serviceDb;
    }

    /**
     * 设置表明
     *
     * @param $table
     */
    public function setTable($table)
    {
        $this->_table = $table;
    }

    /**
     * 获取表名
     *
     * @return string
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * 设置主键
     * 
     * @param string $primaryKey 
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->_primaryKey = $primaryKey;
    }

    /**
     * 获取主键名
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    /**
     * add
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        // 插入数据
        $key = $this->getPrimaryKey();
        $insertId = $this->serviceDb->insert($this->getTable())->values($data)->exec();

        if (is_array($key)) {
            $result = array();
            foreach ($key as $column) {
                $result[] = isset($data[$column]) ? $data[$column] : NULL;
            }

            return $result;
        } else {
            return isset($data[$key]) ? $data[$key] : $insertId;
        }
    }

    /**
     * set
     *
     * @param       $key
     * @param array $data
     * @return int
     */
    public function set($key, array $data)
    {
        $update = $this->serviceDb->update($this->getTable());
        $this->parseKey($update, $key);

        return $update->setMultiple($data)->exec();
    }

    /**
     * remove
     *
     * @param $key
     * @return int
     */
    public function remove($key)
    {
        $delete = $this->serviceDb->delete($this->getTable());
        $this->parseKey($delete, $key);

        return $delete->exec();
    }

    /**
     * get
     *
     * @param string $key
     * @param mixed $columns
     * @return mixed
     */
    public function get($key, $columns = NULL)
    {
        $select = $this->serviceDb->select($this->getTable(), $columns);
        $this->parseKey($select, $key);

        return $select->fetchOne(is_string($columns) ? $columns : NULL);
    }

    /**
     * getMultiple
     *
     * @param array $keys
     * @param mixed $columns
     * @return mixed
     */
    public function getMultiple(array $keys, $columns = NULL)
    {
        $select = $this->serviceDb->select($this->getTable(), $columns);
        $this->parseKeys($select, $keys);

        return $select->fetchAll(is_string($columns) ? $columns : NULL);
    }

    /**
     * findBy
     *
     * @param string $key
     * @param mixed $value
     * @param mixed $columns
     * @return mixed
     */
    public function findBy($key, $value = NULL, $columns = NULL)
    {
        if (is_array($key)) {
            $columns = $value;
            $select = $this->serviceDb->select($this->getTable(), $columns);
            $this->parseWhere($select, $key);
        } else {
            $select = $this->serviceDb->select($this->getTable(), $columns);
            $this->parseWhere($select, array(
                $key    =>  $value
            ));
        }

        return $select->limit(1)->fetchOne(is_string($columns) ? $columns : NULL);
    }

    /**
     * listBy
     *
     * @param array $conditions
     * @param int   $page
     * @param int   $pageSize
     * @param mixed $order
     * @param mixed $columns
     * @return array
     */
    public function listBy(array $conditions, $page, $pageSize, $order = NULL, $columns = NULL)
    {
        $select = $this->serviceDb->select($this->getTable(), $columns)
            ->page($page, $pageSize);
        $this->parseWhere($select, $conditions);

        if (!empty($order)) {
            if (is_array($order)) {
                list ($column, $sort) = $order;
                if ('ASC' == $sort) {
                    $select->orderAsc($column);
                } else {
                    $select->orderDesc($column);
                }
            } else {
                $select->orderAsc($order);
            }
        }

        return $select->fetchAll(is_string($columns) ? $columns : NULL);
    }

    /**
     * countBy
     *
     * @param array $conditions
     * @return int
     */
    public function countBy(array $conditions)
    {
        $select = $this->serviceDb->select($this->getTable(), array('COUNT(*)' => '_count_num'));
        $this->parseWhere($select, $conditions);

        return $select->fetchOne('_count_num');
    }
}

