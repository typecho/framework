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
     * @param mixed $columns
     * @access public
     * @return void
     */
    public function init($table, $columns = NULL)
    {
        $this->setQuery('table', $this->applyPrefix($table));
        if (!empty($columns)) {
            $columns = is_array($columns) ? $columns : array($columns);
            $this->setQuery('columns', $this->applyPrefix($columns));
        }
    }

    /**
     * order  
     * 
     * @param mixed $column 
     * @param mixed $sort 
     * @access private
     * @return Select
     */
    private function order($column, $sort)
    {
        $this->pushQuery('order', array($this->applyPrefix($column), $sort));
        return $this;
    }

    /**
     * join 
     * 
     * @param mixed $op 
     * @param mixed $table 
     * @param mixed $targetColumn 
     * @param mixed $sourceColumn 
     * @access private
     * @return Select
     */
    private function join($op, $table, $targetColumn, $sourceColumn)
    {
        $this->setQuery('join', array($op, $this->applyPrefix($table), $targetColumn, $sourceColumn));
        return $this;
    }

    /**
     * limit  
     * 
     * @param mixed $limit 
     * @access public
     * @return Select
     */
    public function limit($limit)
    {
        $this->setQuery('limit', abs(intval($limit)));
        return $this;
    }

    /**
     * offset  
     * 
     * @param mixed $offset 
     * @access public
     * @return Select
     */
    public function offset($offset)
    {
        $this->setQuery('offset', abs(intval($offset)));
        return $this;
    }

    /**
     * page  
     * 
     * @param mixed $page 
     * @param mixed $pageSize 
     * @access public
     * @return Select
     */
    public function page($page, $pageSize)
    {
        $page = max(1, $page);
        $this->limit($pageSize);
        return $this->offset(($page - 1) * $pageSize);
    }

    /**
     * leftJoin  
     * 
     * @param mixed $table 
     * @param mixed $targetColumn 
     * @param mixed $sourceColumn 
     * @access public
     * @return Select
     */
    public function leftJoin($table, $targetColumn, $sourceColumn = NULL)
    {
        return $this->join('LEFT', $table, $targetColumn, $sourceColumn ?: $targetColumn);
    }

    /**
     * rightJoin  
     * 
     * @param mixed $table 
     * @param mixed $targetColumn 
     * @param mixed $sourceColumn 
     * @access public
     * @return Select
     */
    public function rightJoin($table, $targetColumn, $sourceColumn = NULL)
    {
        return $this->join('RIGHT', $table, $targetColumn, $sourceColumn ?: $targetColumn);
    }

    /**
     * innerJoin  
     * 
     * @param mixed $table 
     * @param mixed $targetColumn 
     * @param mixed $sourceColumn 
     * @access public
     * @return Select
     */
    public function innerJoin($table, $targetColumn, $sourceColumn = NULL)
    {
        return $this->join('INNER', $table, $targetColumn, $sourceColumn ?: $targetColumn);
    }

    /**
     * outerJoin  
     * 
     * @param mixed $table 
     * @param mixed $targetColumn 
     * @param mixed $sourceColumn 
     * @access public
     * @return Select
     */
    public function outerJoin($table, $targetColumn, $sourceColumn = NULL)
    {
        return $this->join('OUTER', $table, $targetColumn, $sourceColumn ?: $targetColumn);
    }

    /**
     * orderAsc
     *
     * @param $column
     * @return Select
     */
    public function orderAsc($column)
    {
        return $this->order($column, 'ASC');
    }

    /**
     * orderDesc
     *
     * @param $column
     * @return Select
     */
    public function orderDesc($column)
    {
        return $this->order($column, 'DESC');
    }

    /**
     * group
     *
     * @param $column
     * @return Select
     */
    public function group($column)
    {
        $this->setQuery('group', $this->applyPrefix($column));
        return $this;
    }

    /**
     * fetchOne
     *
     * @param string $column
     * @access public
     * @return mixed
     */
    public function fetchOne($column = NULL)
    {
        $handle = $this->getAdapter()->query((string) $this);
        $result = $this->getAdapter()->fetchOne($handle);

        if (empty($result)) {
            return NULL;
        } else {
            return empty($column) ? $result : $result[$column];
        }
    }

    /**
     * fetchAll
     *
     * @param string $column
     * @return array
     */
    public function fetchAll($column = NULL)
    {
        $handle = $this->getAdapter()->query((string) $this);
        $result = $this->getAdapter()->fetchAll($handle);

        if (empty($result)) {
            return array();
        } else {
            return empty($column) ? $result : array_map(function ($row) use ($column) {
                return $row[$column];
            }, $result);
        }
    }
}

