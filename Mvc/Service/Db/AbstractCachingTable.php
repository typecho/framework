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
     * getCacheKey
     *
     * @return string
     */
    public function getCacheKey()
    {
        $args = func_get_args();
        $args = array_map(function ($arg) {
            return is_array($arg) ? implode('-', $arg) : $arg;
        }, $args);
        return $this->getTable() . ':' . implode(':', $args);
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
            $cacheKey = $this->getCacheKey($key);
            if ($this->serviceDbCache->getHash($cacheKey)) {
                $this->serviceDbCache->setHash($cacheKey, $data);
            }
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
            $data = parent::get($insertId);
            $this->serviceDbCache->setHash($this->getCacheKey($insertId), $data);
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
            $this->serviceDbCache->removeHash($this->getCacheKey($key));
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
        $cacheKey = $this->getCacheKey($key);
        $cached = $this->serviceDbCache->getHash($cacheKey);
        if (empty($cached)) {
            $cached = parent::get($key);
            if (!empty($cached)) {
                $this->serviceDbCache->setHash($cacheKey, $cached);
            }
        }

        if (empty($cached)) {
            return NULL;
        }

        if (is_string($columns)) {
            return $cached[$columns];
        } else if (is_array($columns)) {
            $cached = array_intersect_key($cached, array_flip($columns));
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
    public function getMultiple(array $keys, $columns = NULL)
    {
        $cachedKeys = array_map(array($this, 'getCacheKey'), $keys);
        $cached = $this->serviceDbCache->getMultipleHash($cachedKeys);
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
                $this->serviceDbCache->setHash($this->getCacheKey($val), $data[$index]);
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

        return $cached;
    }
}

