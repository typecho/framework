<?php

namespace TE\Cache;

/**
 * Redis 
 * 
 * @uses CacheInterface
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Redis implements CacheInterface
{
    /**
     * redis对象
     *
     * @var \Redis
     */
    private $_redis;

    /**
     * @param string $host
     * @param int    $port
     * @param int    $timeout
     * @param int    $db
     */
    public function __construct($host = 'localhost', $port = 6379, $timeout = 30, $db = 0)
    {
        $this->_redis = new \Redis();
        $this->_redis->connect($host, $port, $timeout);
        $this->_redis->select($db);
    }

    /**
     * 设置缓存
     *
     * @param string $key
     * @param array  $data
     */
    public function set($key, array $data)
    {
        $this->_redis->hMSet($key, $data);
    }

    /**
     * 获取缓存
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->_redis->hGetAll($key);
    }

    /**
     * 获取多个缓存
     *
     * @param array $keys
     * @return array
     */
    public function getMultiple(array $keys)
    {
        $pipeline = $this->_redis->pipeline();
        foreach ($keys as $key) {
            $pipeline->hGetAll($key);
        }

        return $pipeline->exec();
    }

    /**
     * 删除缓存
     *
     * @param string $key
     */
    public function remove($key)
    {
        $this->_redis->delete($key);
    }

    /**
     * 获取redis对象
     *
     * @return mixed|\Redis
     */
    public function getCache()
    {
        return $this->_redis;
    }
}

