<?php

namespace TE\Cache;

/**
 * HashCacheInterface  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
interface HashCacheInterface
{
    /**
     * 设置缓存
     * 
     * @param string $key 
     * @param array $data 
     */
    public function setHash($key, array $data);

    /**
     * 获取缓存
     * 
     * @param string $key 
     * @return mixed
     */
    public function getHash($key);

    /**
     * 获取多个缓存
     * 
     * @param array $keys
     * @return array
     */
    public function getMultipleHash(array $keys);

    /**
     * 删除缓存
     *
     * @param string $key
     */
    public function removeHash($key);
}

