<?php
/**
 * Created by PhpStorm.
 * User: qining
 * Date: 14-3-14
 * Time: 下午4:51
 */

namespace TE\Db;

use TE\Db\Query\AbstractQuery;

class Table
{
    /**
     * @var Connector
     */
    private $_connector;

    /**
     * @var string
     */
    private $_name;

    /**
     * init table
     *
     * @param string $name table name
     * @param Connector $connector
     */
    public function __construct($name, Connector $connector)
    {
        $this->_connector = $connector;
        $this->_name = $name;
    }

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
                call_user_func_array(array($query, 'where'), $where);
            } else {
                $query->where("{$index} = ?", $where);
            }
        }
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
        return $this->_connector->insert($this->_name)->values($data)->exec();
    }

    /**
     * setBy
     *
     * @param array $conditions
     * @param array $data
     * @return int
     */
    public function setBy(array $conditions, array $data)
    {
        $update = $this->_connector->update($this->_name);
        $this->parseWhere($update, $conditions);

        return $update->setMultiple($data)->exec();
    }

    /**
     * removeBy
     *
     * @param array $conditions
     * @return int
     */
    public function removeBy(array $conditions)
    {
        $delete = $this->_connector->delete($this->_name);
        $this->parseWhere($delete, $conditions);

        return $delete->exec();
    }

    /**
     * findBy
     *
     * @param array $conditions
     * @param mixed $columns
     * @return mixed
     */
    public function findBy(array $conditions, $columns = NULL)
    {
        $select = $this->_connector->select($this->_name, $columns);
        $this->parseWhere($select, $conditions);

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
        $select = $this->_connector->select($this->_name, $columns);
        $this->parseWhere($select, $conditions);

        if ($pageSize > 0) {
            $select->page($page, $pageSize);
        }

        if (!empty($order)) {
            if (is_array($order)) {
                foreach ($order as $column => $sort) {
                    if (is_int($column)) {
                        $select->orderAsc($sort);
                    } else if ('ASC' == strtoupper($sort)) {
                        $select->orderAsc($column);
                    } else {
                        $select->orderDesc($column);
                    }
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
        $select = $this->_connector->select($this->_name, array('COUNT(*)' => '_count_num'));
        $this->parseWhere($select, $conditions);

        return $select->fetchOne('_count_num');
    }
}
