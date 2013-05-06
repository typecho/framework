<?php

namespace TE\Mvc\Service\Db;

use TE\Mvc\Base;

/**
 * Class AbstractTable
 *
 * @package TE\Mvc\Service
 */
abstract class AbstractTable extends Base
{
    /**
     * 获取Db对象
     *
     * @return \TE\Db\Connector
     */
    abstract public function getDb();

    /**
     * 获取表名
     *
     * @return string
     */
    abstract public function getTable();

    /**
     * 获取主键名
     *
     * @return string
     */
    abstract public function getPrimaryKey();

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
        $insertId = $this->getDb()->insert($this->getTable())->values($data)->exec();
        return isset($data[$key]) ? $data[$key] : $insertId;
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
        return $this->getDb()->update($this->getTable())->setMultiple($data)
            ->where($this->getPrimaryKey() . ' = ?', $key)
            ->exec();
    }

    /**
     * remove
     *
     * @param $key
     * @return int
     */
    public function remove($key)
    {
        return $this->getDb()->delete($this->getTable())
            ->where($this->getPrimaryKey() . ' = ?', $key)
            ->exec();
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
        $result = $this->getDb()->select($this->getTable(), $columns)
            ->where($this->getPrimaryKey() . ' = ?', $key)
            ->fetchOne();

        return is_string($columns) ? $result[$columns] : $result;
    }

    /**
     * getMultiple
     *
     * @param array $keys
     * @param mixed $columns
     * @return array
     */
    public function getMultiple(array $keys, $columns = NULL)
    {
        $result = $this->getDb()->select($this->getTable(), $columns)
            ->where($this->getPrimaryKey() . ' IN ?', $keys)
            ->fetchAll();

        return is_string($columns) ? array_map(function ($row) use ($columns) {
            return $row[$columns];
        }, $result) : $result;
    }

    /**
     * findBy
     *
     * @param string $key
     * @param mixed $value
     * @param mixed $columns
     * @return mixed
     */
    public function findBy($key, $value, $columns = NULL)
    {
        $result = $this->getDb()->select($this->getTable(), $columns)
            ->where("{$key} = ?", $value)
            ->limit(1)
            ->fetchOne();

        return is_string($columns) ? $result[$columns] : $result;
    }
}

