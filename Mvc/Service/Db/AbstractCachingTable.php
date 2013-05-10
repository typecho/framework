<?php

namespace TE\Mvc\Service\Db;

use TE\Cache\HashCacheInterface;

/**
 * Class AbstractCachingTable
 *
 * @package TE\Mvc\Service\Db
 */
abstract class AbstractCachingTable extends AbstractTable
{
    /**
     * @var HashCacheInterface
     */
    protected $serviceDbCache;

    /**
     * setServiceDbCache
     *
     * @param HashCacheInterface $serviceDbCache
     */
    public function setServiceDbCache(HashCacheInterface $serviceDbCache)
    {
        $this->serviceDbCache = $serviceDbCache;
    }

    /**
     * set
     *
     * @param string $key
     * @param mixed $data
     * @return int
     */
    public function set($key, $data)
    {
        $data = $this->castData($data);
        $affectedRows = parent::set($key, $data);
        if ($affectedRows > 0) {
            $this->serviceDbCache->removeHash($this->getTable() . ':' . $key);
        }

        return $affectedRows;
    }

    /**
     * add
     *
     * @param mixed $data
     * @return mixed
     */
    public function add($data)
    {
        $data = $this->castData($data);
        $insertId = parent::add($data);
        if ($insertId) {
            $data = $this->serviceDb->select($this->getTable())
                ->where($this->getPrimaryKey() . ' = ?', $insertId)
                ->fetchOne();
            $this->serviceDbCache->setHash($this->getTable() . ':' . $insertId, $data);
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
            $this->serviceDbCache->removeHash($this->getTable() . ':' . $key);
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
        $cached = $this->serviceDbCache->getHash($key);
        if (empty($cached)) {
            $cached = parent::get($key);
            if (!empty($cached)) {
                $cached = $this->castData($cached);
                $this->serviceDbCache->setHash($this->getTable() . ':' . $key, $cached);
            }
        }

        if (is_string($columns)) {
            return $cached[$columns];
        } else if (is_array($columns)) {
            $cached = array_intersect_key($cached, array_flip($columns));
        }

        return $this->fetchData($cached);
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
        $cached = $this->serviceDbCache->getMultipleHash($keys);
        $missed = array();

        foreach ($cached as $key => $val) {
            if (empty($val)) {
                $missed[$key] = $keys[$key];
            }
        }

        if (!empty($missed)) {
            $data = parent::getMultiple($missed);
            $index = 0;

            foreach ($missed as $key => $val) {
                $cached[$key] = $data[$index];
                $this->serviceDbCache->setHash($this->getTable() . ':' . $val, $data[$index]);
                $index ++;
            }
        }

        if (is_string($columns)) {
            return array_map(function ($item) use ($columns) {
                return $item[$columns];
            }, $cached);
        } else if (is_array($columns)) {
            $columns = array_flip($columns);
            $cached = array_map(function ($item) use ($columns) {
                return array_intersect_key($item,$columns);
            }, $cached);
        }

        return $this->fetchData($cached);
    }
}

