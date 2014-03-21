<?php

namespace TE\Db\Adapter;

/**
 * Mysql  
 * 
 * @uses AbstractAdapter
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Mysql extends AbstractAdapter
{
    /**
     * 数据库连接字符串标示
     *
     * @access private
     * @var resource
     */
    private $_dbLink;

    /**
     * @param        $db
     * @param        $user
     * @param null   $password
     * @param string $host
     * @param int    $port
     * @param string $charset
     * @throws AdapterException
     */
    public function __construct($db, $user, $password = NULL, $host = 'localhost', $port = 3306, $charset = 'utf8')
    {
        $this->_dbLink = @mysql_connect("{$host}:{$port}", $user, $password, true);
        if ($this->_dbLink) {
            if (@mysql_select_db($db, $this->_dbLink)) {
                mysql_query('SET NAMES ' . $this->quoteValue($charset));
                return;
            }
            
            throw new AdapterException(mysql_error($this->_dbLink));
        }

        throw new AdapterException(mysql_error());
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
        return mysql_fetch_assoc($handle);
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
        while ($row = mysql_fetch_assoc($handle)) {
            $result[] = $row;
        }

        return $result;
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
        $handle = @mysql_query($query, $this->_dbLink);
        if (!$handle) {
            throw new AdapterException(@mysql_error($this->_dbLink), mysql_errno($this->_dbLink));
        }

        return $handle;
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
        return mysql_insert_id($this->_dbLink);
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
        return mysql_affected_rows($this->_dbLink);
    }

    /**
     * 引号转义函数
     *
     * @param string $string 需要转义的字符串
     * @return string
     */
    public function quoteValue($string)
    {
        return '\'' . str_replace(array('\'', '\\'), array('\'\'', '\\\\'), $string) . '\'';
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
        return '`' . $string . '`';
    }
}

