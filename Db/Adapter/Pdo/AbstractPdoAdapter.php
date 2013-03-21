<?php

namespace TE\Db\Adapter\Pdo;

use TE\Db\Adapter\AbstractAdapter;
use TE\Db\Adapter\AdapterException;


/**
 * AbstractPdoAdapter  
 * 
 * @uses AbstractAdapter
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractPdoAdapter extends AbstractAdapter
{
    /**
     * 数据库对象
     *
     * @access protected
     * @var PDO
     */
    protected $_pdo;

    /**
     * __construct 
     * 
     * @param mixed $dsn 
     * @param mixed $user 
     * @param mixed $password 
     * @param array $options 
     * @access public
     * @return void
     */
    public function __construct($dsn, $user = NULL, $password = NULL, array $options = array())
    {
        PDO::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $this->_pdo = new \PDO($dsn, $user, $password, array_merge(array(
                \PDO::ATTR_TIMEOUT => 10
            ), $options));
        } catch (\PDOException $e) {
            throw new AdapterException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * query 
     * 
     * @param mixed $query 
     * @access public
     * @return void
     */
    public function query($query)
    {
        try {
            return $this->_pdo->query($query);
        } catch (\PDOException $e) {
            throw new AdapterException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * fetchOne 
     * 
     * @param mixed $handle 
     * @access public
     * @return void
     */
    public function fetchOne($handle)
    {
        return $handle->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * fetchAll  
     * 
     * @param mixed $handle 
     * @access public
     * @return void
     */
    public function fetchAll($handle)
    {
        return $handle->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * 引号转义函数
     *
     * @param string $string 需要转义的字符串
     * @return string
     */
    public function quoteValue($string)
    {
        return $this->_pdo->quote($string);
    }

    /**
     * affectedRows 
     * 
     * @param mixed $handle 
     * @access public
     * @return void
     */
    public function affectedRows($handle)
    {
        return $handle->rowCount();
    }
    
    /**
     * lastInsertId 
     * 
     * @param mixed $handle 
     * @access public
     * @return void
     */
    public function lastInsertId($handle)
    {
        return $this->_pdo->lastInsertId();
    }
}
