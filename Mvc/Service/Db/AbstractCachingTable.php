<?php

namespace TE\Mvc\Service\Db;

use TE\Cache\CacheInterface;

/**
 * Class AbstractCachingTable
 *
 * @package TE\Mvc\Service\Db
 */
abstract class AbstractCachingTable extends AbstractTable
{
    /**
     * @var CacheInterface
     */
    protected $serviceDbCache;

    /**
     * @param \TE\Cache\CacheInterface $serviceDbCache
     */
    public function setServiceDbCache(CacheInterface $serviceDbCache)
    {
        $this->serviceDbCache = $serviceDbCache;
    }

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
            $this->serviceDbCache->set($key, $data);
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
            $data = $this->serviceDb->select($this->getTable())
                ->where($this->getPrimaryKey() . ' = ?', $insertId)
                ->fetchOne();
            $this->serviceDbCache->set($insertId, $data);
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
            $this->serviceDbCache->remove($key);
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
        $cached = $this->serviceDbCache->get($key);
        if (false === $cached) {
            $cached = parent::get($key);
            $this->serviceDbCache->set($key, $cached);
        }

        if (is_string($columns)) {
            return $cached[$columns];
        } else if (is_array($columns)) {
            return array_filter($cached, function () use (&$cached, $columns) {
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
        $cached = $this->serviceDbCache->getMultiple($keys);
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
                return array_filter($item, function () use (&$item, $columns) {
                    $key = key($item);
                    next($item);
                    return in_array($key, $columns);
                });
            }, $cached);
        }

        return $cached;
    }
}

