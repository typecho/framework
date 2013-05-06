<?php

namespace TE\Mvc\Service\Db;

/**
 * Class AbstractCachingTable
 *
 * @package TE\Mvc\Service
 */
abstract class AbstractCachingTable extends AbstractTable
{
    /**
     * setCache 
     * 
     * @param mixed $key 
     * @param array $data 
     * @access protected
     */
    abstract protected function setCache($key, array $data);

    /**
     * getCache  
     * 
     * @param mixed $key 
     * @access protected
     * @return mixed
     */
    abstract protected function getCache($key);

    /**
     * getCacheMultiple
     * 
     * @param array $keys
     * @access protected
     * @return array
     */
    abstract protected function getCacheMultiple(array $keys);

    /**
     * deleteCache  
     * 
     * @param mixed $key 
     * @access protected
     */
    abstract protected function deleteCache($key);

    /**
     * set
     *
     * @param string $key
     * @param array $data
     * @return int
     */
    public function set($key, array $data)
    {
        $affectedRows = parent::set($key, $data);
        if ($affectedRows > 0) {
            $this->setCache($key, $data);
        }

        return $affectedRows;
    }

    /**
     * add
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $insertId = parent::add($data);
        if ($insertId) {
            $data = $this->getDb()->select($this->getTable())
                ->where($this->getPrimaryKey() . ' = ?', $insertId)
                ->fetchOne();
            $this->setCache($insertId, $data);
        }

        return $insertId;
    }

    /**
     * remove
     *
     * @param $key
     * @return int
     */
    public function remove($key)
    {
        $affectedRows = parent::remove($key);
        if ($affectedRows > 0) {
            $this->deleteCache($key);
        }

        return $affectedRows;
    }

    /**
     * get
     *
     * @param string $key
     * @param mixed  $columns
     * @return array|mixed
     */
    public function get($key, $columns = NULL)
    {
        $cached = $this->getCache($key);
        if (false === $cached) {
            $cached = parent::get($key);
            $this->setCache($key, $cached);
        }

        if (is_string($columns)) {
            return $cached[$columns];
        } else if (is_array($columns)) {
            return array_filter($cached, function ($item) use (&$cached, $columns) {
                $key = key($cached);
                next($cached);
                return in_array($key, $columns);
            });
        }

        return $cached;
    }

    /**
     * getMultiple
     *
     * @param array $keys
     * @param mixed $columns
     * @return array
     */
    public function getMultiple($keys, $columns = NULL)
    {
        $cached = $this->getMultiple($keys);
        $missed = array();

        foreach ($cached as $key => $val) {
            if (false === $val) {
                $missed[$key] = $keys[$key];
            }
        }

        if (!empty($missed)) {
            $data = parent::getMultiple($missed);
            $index = 0;

            foreach ($missed as $key => $val) {
                $cached[$key] = $data[$index];
                $index ++;
            }
        }

        if (is_string($columns)) {
            return array_map(function ($item) use ($columns) {
                return $item[$columns];
            }, $cached);
        } else if (is_array($columns)) {
            return array_map(function ($item) use ($columns) {
                return array_filter($item, function ($row) use (&$item, $columns) {
                    $key = key($item);
                    next($item);
                    return in_array($key, $columns);
                });
            }, $cached);
        }

        return $cached;
    }
}

