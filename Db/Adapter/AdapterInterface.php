<?php

namespace TE\Db\Adapter;

use TE\Db\Query\Select as SelectQuery;
use TE\Db\Query\Delete as DeleteQuery;
use TE\Db\Query\Insert as InsertQuery;
use TE\Db\Query\Update as UpdateQuery;
use TE\Db\Query\Query as SqlQuery;

/**
 * AdapterInterface 
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
interface AdapterInterface
{
    /**
     * fetchOne  
     * 
     * @param mixed $handle 
     * @access public
     * @return array
     */
    public function fetchOne($handle);

    /**
     * fetchAll  
     * 
     * @param mixed $handle 
     * @access public
     * @return array
     */
    public function fetchAll($handle);

    /**
     * query  
     * 
     * @param mixed $query 
     * @access public
     * @return mixed
     */
    public function query($query);

    /**
     * lastInsertId  
     * 
     * @param mixed $handle 
     * @access public
     * @return integer
     */
    public function lastInsertId($handle);

    /**
     * affectedRows  
     * 
     * @param mixed $handle 
     * @access public
     * @return integer
     */
    public function affectedRows($handle);

    /**
     * parseSelect 
     * 
     * @param SelectQuery $query 
     * @access public
     * @return string
     */
    public function parseSelect(SelectQuery $query);

    /**
     * parseDelete  
     * 
     * @param DeleteQuery $query 
     * @access public
     * @return string
     */
    public function parseDelete(DeleteQuery $query);

    /**
     * parseUpdate  
     * 
     * @param UpdateQuery $query 
     * @access public
     * @return string
     */
    public function parseUpdate(UpdateQuery $query);

    /**
     * parseInsert  
     * 
     * @param InsertQuery $query 
     * @access public
     * @return string
     */
    public function parseInsert(InsertQuery $query);

    /**
     * parseQuery  
     * 
     * @param SqlQuery $query 
     * @access public
     * @return string
     */
    public function parseQuery(SqlQuery $query);
}

