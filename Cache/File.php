<?php

namespace TE\Cache;

/**
 * 文件式缓存
 *
 * @uses CacheInterface
 * @author Byends <byends@gmail.com>
 * @license GNU General Public License 2.0
 */
class File implements CacheInterface
{
    /**
     * 缓存路径
     */
    private $_cacheDir;

    /**
     * 有效时间
     */
    private $_lifeTime;

    /**
     * @param $cacheDir
     * @param int $lifeTime (0 || null 表示永不过期,默认900秒,15分钟)
     */
    public function __construct($cacheDir, $lifeTime = 900)
    {
        $this->_cacheDir = rtrim($cacheDir, '/').'/';
        $this->_lifeTime = $lifeTime;
    }

    /**
     * 获取原始对象
     *
     * @return mixed
     */
    public function getCache()
    {}

    /**
     * 设置缓存
     *
     * @param string $key
     * @param string $data
     */
    public function set($key, $data)
    {
        $data = array(
            /** 记录时效 */
            'II' => pack('II', $this->_lifeTime, time()),
            'data' => $data
        );

        $content = '<?php ';
        $content .= 'return ' . var_export($data, true) .';';
        $content .= '?>';

        /** 写入缓存 */
        file_put_contents($this->_cacheDir . $key . '.php', $content, LOCK_EX);

        /** 释放内存  */
        unset($data, $content);
    }

    /**
     * 获取缓存
     *
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        $path = $this->_cacheDir . $key . '.php';
        $cache = array();

        if (file_exists($path)) {
            $cache = include $path;
        }

        if ($cache) {
            $tmp = isset($cache['II']) && $cache['II'] ? unpack('Il/IL', $cache['II']) : '';

            /** 检测时效性 */
            if ($tmp && (!$tmp['l'] || (time() - $tmp['L'] <= $tmp['l']))) {
                return $cache['data'];
            }
        }

        /** 清除已经过时的缓存  */
        @unlink($path);
        return '';
    }

    /**
     * 获取多个缓存
     *
     * @param array $keys
     * @return array
     */
    public function getMultiple(array $keys)
    {
        $cache = array();
        foreach ($keys as $v) {
            $cache[$v] = $this->get($v);
        }

        return $cache;
    }

    /**
     * 删除缓存
     *
     * @param string $key
     */
    public function remove($key)
    {
        $path = $this->_cacheDir . $key . '.php';
        @unlink($path);
    }
}
