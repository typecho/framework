<?php

namespace TE\Db\Adapter;

/**
 * SQLite 
 * 
 * @uses AbstractAdapter
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class SQLite extends AbstractAdapter
{
    /**
     * 数据库标示
     *
     * @access private
     * @var resource
     */
    private $_dbHandle;

    /**
     * @param $file
     * @throws AdapterException
     */
    public function __construct($file)
    {
        $this->_dbHandle = sqlite_open($file, 0666, $error);

        if (!$this->_dbHandle) {
            throw new AdapterException($error);
        }
    }

    /**
     * 过滤字段名
     *
     * @access private
     * @param mixed $result
     * @return array
     */
    private function filterColumnName($result)
    {
        /** 如果结果为空,直接返回 */
        if (!$result) {
            return $result;
        }

        $tResult = array();

        /** 遍历数组 */
        foreach ($result as $key => $val) {
            /** 按点分隔 */
            if (false !== ($pos = strpos($key, '.'))) {
                $key = substr($key, $pos + 1);
            }

            /** 按引号分割 */
            if (false === ($pos = strpos($key, '"'))) {
                $tResult[$key] = $val;
            } else {
                $tResult[substr($key, $pos + 1, -1)] = $val;
            }
        }

        return $tResult;
    }

    /**
     * query 
     * 
     * @param mixed $query 
     * @access public
     * @return mixed
     * @throws AdapterException
     */
    public function query($query)
    {
        $handle = @sqlite_query($query, $this->_dbHandle);
        if (!$handle) {
            $errorCode = sqlite_last_error($this->_dbHandle);
            throw new AdapterException(sqlite_error_string($errorCode), $errorCode);
        }

        return $handle;
    }

    /**
     * fetchOne  
     * 
     * @param mixed $handle 
     * @access public
     * @return array
     */
    public function fetchOne($handle)
    {
        return $this->filterColumnName(sqlite_fetch_array($handle, SQLITE_ASSOC));
    }

    /**
     * fetchAll  
     * 
     * @param mixed $handle 
     * @access public
     * @return array
     */
    public function fetchAll($handle)
    {
        $result = array();
        if ($row = sqlite_fetch_array($handle, SQLITE_ASSOC)) {
            $result[] = $this->filterColumnName($row);
        }

        return $result;
    }

    /**
     * 引号转义函数
     *
     * @param string $string 需要转义的字符串
     * @return string
     */
    public function quoteValue($string)
    {
        return '\'' . str_replace('\'', '\'\'', $string) . '\'';
    }

    /**
     * 对象引号过滤
     *
     * @access public
     * @param string $string
     * @return string
     */
    public function quoteColumn($string)
    {
        return '"' . $string . '"';
    }

    /**
     * affectedRows 
     * 
     * @param mixed $handle 
     * @access public
     * @return integer
     */
    public function affectedRows($handle)
    {
        return sqlite_changes($this->_dbHandle);
    }
    
    /**
     * lastInsertId 
     * 
     * @param mixed $handle 
     * @access public
     * @return integer
     */
    public function lastInsertId($handle)
    {
        return sqlite_last_insert_rowid($this->_dbHandle);
    }
}

