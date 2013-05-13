<?php

namespace TE\Cache;

/**
 * CacheInterface 
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
interface CacheInterface
{
    /**
     * 获取原始对象
     *
     * @return mixed
     */
    public function getCache();

    /**
     * 设置缓存
     *
     * @param string $key
     * @param string $data
     */
    public function set($key, $data);

    /**
     * 获取缓存
     *
     * @param string $key
     * @return string
     */
    public function get($key);

    /**
     * 获取多个缓存
     *
     * @param array $keys
     * @return array
     */
    public function getMultiple(array $keys);

    /**
     * 删除缓存
     *
     * @param string $key
     */
    public function remove($key);
}

