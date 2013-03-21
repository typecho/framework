<?php

namespace TE\Db\Query;

/**
 * Select  
 * 
 * @uses AbstractQuery
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Select extends AbstractQuery
{
    /**
     * init  
     * 
     * @param mixed $table 
     * @param array $columns
     * @access public
     * @return void
     */
    public function init($table, array $columns = array())
    {
        $this->setQuery('table', $this->applyPrefix($table));
        $this->setQuery('columns', $this->applyPrefix($columns));
    }

    /**
     * order  
     * 
     * @param mixed $column 
     * @param mixed $sort 
     * @access private
     * @return void
     */
    private function order($column, $sort)
    {
        $this->pushQuery('order', array($this->applyPrefix($column), $sort));
    }

    /**
     * join 
     * 
     * @param mixed $op 
     * @param mixed $table 
     * @param mixed $targetColumn 
     * @param mixed $sourceColumn 
     * @access private
     * @return void
     */
    private function join($op, $table, $targetColumn, $sourceColumn)
    {
        $this->setQuery('join', array($op, $this->applyPrefix($table), $targetColumn, $sourceColumn));
    }

    /**
     * limit  
     * 
     * @param mixed $limit 
     * @access public
     * @return void
     */
    public function limit($limit)
    {
        $this->setQuery('limit', abs(intval($limit)));
    }

    /**
     * offset  
     * 
     * @param mixed $offset 
     * @access public
     * @return void
     */
    public function offset($offset)
    {
        $this->setQuery('offset', abs(intval($offset)));
    }

    /**
     * page  
     * 
     * @param mixed $page 
     * @param mixed $pageSize 
     * @access public
     * @return void
     */
    public function page($page, $pageSize)
    {
        $page = max(1, $page);
        $this->limit($pageSize);
        $this->offset(($page - 1) * $pageSize);
    }

    /**
     * leftJoin  
     * 
     * @param mixed $table 
     * @param mixed $targetColumn 
     * @param mixed $sourceColumn 
     * @access public
     * @return void
     */
    public function leftJoin($table, $targetColumn, $sourceColumn = NULL)
    {
        $this->join('LEFT', $table, $targetColumn, $sourceColumn ?: $targetColumn);
    }

    /**
     * rightJoin  
     * 
     * @param mixed $table 
     * @param mixed $targetColumn 
     * @param mixed $sourceColumn 
     * @access public
     * @return void
     */
    public function rightJoin($table, $targetColumn, $sourceColumn = NULL)
    {
        $this->join('RIGHT', $table, $targetColumn, $sourceColumn ?: $targetColumn);
    }

    /**
     * innerJoin  
     * 
     * @param mixed $table 
     * @param mixed $targetColumn 
     * @param mixed $sourceColumn 
     * @access public
     * @return void
     */
    public function innerJoin($table, $targetColumn, $sourceColumn = NULL)
    {
        $this->join('INNER', $table, $targetColumn, $sourceColumn ?: $targetColumn);
    }

    /**
     * outerJoin  
     * 
     * @param mixed $table 
     * @param mixed $targetColumn 
     * @param mixed $sourceColumn 
     * @access public
     * @return void
     */
    public function outerJoin($table, $targetColumn, $sourceColumn = NULL)
    {
        $this->join('OUTER', $table, $targetColumn, $sourceColumn ?: $targetColumn);
    }

    /**
     * order  
     * 
     * @param mixed $columns 
     * @param string $sort 
     * @access public
     * @return void
     */
    public function orderAsc($column)
    {
        $this->order($column, 'ASC');
    }
    
    /**
     * order  
     * 
     * @param mixed $columns 
     * @param string $sort 
     * @access public
     * @return void
     */
    public function orderDesc($column)
    {
        $this->order($column, 'DESC');
    }

    /**
     * group  
     * 
     * @param mixed $column 
     * @access public
     * @return void
     */
    public function group($column)
    {
        $this->setQuery('group', $this->applyPrefix($column));
    }
}

