<?php

namespace TE\Db\Adapter;

use TE\Db\Query\AbstractQuery as Query;
use TE\Db\Query\Select as SelectQuery;
use TE\Db\Query\Delete as DeleteQuery;
use TE\Db\Query\Insert as InsertQuery;
use TE\Db\Query\Update as UpdateQuery;
use TE\Db\Query\Query as SqlQuery;

/**
 * AbstractAdapter  
 * 
 * @uses AdapterInterface
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * quoteColumn  
     * 
     * @param mixed $column 
     * @abstract
     * @access public
     * @return string
     */
    abstract public function quoteColumn($column);

    /**
     * quoteValue  
     * 
     * @param mixed $value 
     * @abstract
     * @access public
     * @return string
     */
    abstract public function quoteValue($value);

    /**
     * parseColumn  
     * 
     * @param mixed $column 
     * @access protected
     * @return string
     */
    protected function parseColumn($column)
    {
        $column = trim($column);

        if (preg_match("/^[_a-z0-9\.]+$/i", $column)) {
            $parts = explode('.', $column);
            $last = count($parts) - 1;
            $parts[$last] = $this->quoteColumn($parts[$last]);
            return implode('.', $parts);
        } else if (preg_match("/^([_a-z0-9]+)\s*\((.*?)\)$/i", $column, $matches)) {
            return $matches[1] . '(' . $this->parseColumn($matches[2]) . ')';
        }

        return $column;
    }

    /**
     * parseValue
     *
     * @param $value
     * @return string
     */
    protected function parseValue($value)
    {
        return is_array($value) ? '(' . implode(',', array_map(array($this, 'quoteValue'), $value)) . ')'
            : $this->quoteValue($value);
    }

    /**
     * parseWhere  
     * 
     * @param Query $query 
     * @access protected
     * @return string
     */
    protected function parseWhere(Query $query)
    {
        $where = $query->getQuery('where');
        $str = '';

        if (!empty($where)) {
            $str = 'WHERE 1 = 1';

            foreach ($where as $condition) {
                list($op, $args) = $condition;
                $expression = array_shift($args);
                $args = array_map(array($this, 'parseValue'), $args);
                array_unshift($args, str_replace('?', '%s', $expression));
                $str .= " {$op} (" . call_user_func_array('sprintf', $args) . ')';
            }
        }

        return $str;
    }

    /**
     * applyTemplate  
     * 
     * @param mixed $template 
     * @param array $value 
     * @access protected
     * @return string
     */
    protected function applyTemplate($template, array $value)
    {
        return trim(preg_replace_callback("/%([_a-z0-9-]+?)%/i", function ($matches) use ($value) {
            $key = $matches[1];
            return isset($value[$key]) ? $value[$key] : '';
        }, $template));
    }

    /**
     * parseSelect 
     * 
     * @param SelectQuery $query 
     * @access public
     * @return string
     */
    public function parseSelect(SelectQuery $query)
    {
        $table = $this->quoteColumn($query->getQuery('table'));

        $columns = '*';
        if ($query->getQuery('columns')) {
            $items = $query->getQuery('columns');
            $columns = array();

            foreach ($items as $key => $val) {
                if (is_string($key)) {
                    $columns[] = $this->parseColumn($key) . ' AS ' . $this->quoteColumn($val);
                } else {
                    $columns[] = $this->parseColumn($val);
                }
            }

            $columns = implode(', ', $columns);
        }

        $join = '';
        if ($query->getQuery('join')) {
            list ($op, $joinTable, $targetColumn, $sourceColumn) = $query->getQuery('join');
            $join .= "{$op} JOIN {$joinTable} ON {$joinTable}." . $this->quoteColumn($targetColumn)
                . " = {$table}." . $this->quoteColumn($sourceColumn);
        }

        $limit = '';
        if (NULL !== $query->getQuery('limit')) {
            $limit .= 'LIMIT ' . $query->getQuery('limit');
        }

        $offset = '';
        if (NULL !== $query->getQuery('offset')) {
            $offset .= 'OFFSET ' . $query->getQuery('offset');
        }

        $group = '';
        if ($query->getQuery('group')) {
            $group .= 'GROUP BY ' . $this->parseColumn($query->getQuery('group'));
        }
        
        $order = '';
        if ($query->getQuery('order')) {
            $items = $query->getQuery('order');
            $order = array();

            foreach ($items as $val) {
                list ($column, $sort) = $val;
                $order[] = $this->parseColumn($column) . " {$sort}";
            }
            
            $order = 'ORDER BY ' . implode(', ', $order);
        }

        return $this->applyTemplate('SELECT %columns% FROM %table% %join% %where% %group% %order% %limit% %offset%', array(
            'columns'   =>  $columns,
            'table'     =>  $table,
            'join'      =>  $join,
            'where'     =>  $this->parseWhere($query),
            'group'     =>  $group,
            'order'     =>  $order,
            'limit'     =>  $limit,
            'offset'    =>  $offset
        ));
    }

    /**
     * parseDelete  
     * 
     * @param DeleteQuery $query 
     * @access public
     * @return string
     */
    public function parseDelete(DeleteQuery $query)
    {
        return $this->applyTemplate('DELETE FROM %table% %where%', array(
            'table' =>  $this->quoteColumn($query->getQuery('table')),
            'where' =>  $this->parseWhere($query)
        ));
    }

    /**
     * parseUpdate  
     * 
     * @param UpdateQuery $query 
     * @access public
     * @return string
     */
    public function parseUpdate(UpdateQuery $query)
    {
        $update = array();

        if ($query->getQuery('set')) {
            $sets = $query->getQuery('set');
            foreach ($sets as $set) {
                list ($key, $val) = $set;
                $update[] = $this->quoteColumn($key) . ' = ' . $this->quoteValue($val);
            }
        }

        if ($query->getQuery('xset')) {
            $xsets = $query->getQuery('xset');
            foreach ($xsets as $val) {
                list ($op, $column, $step) = $val;
                $column = $this->quoteColumn($column);
                $update[] = $column . ' = ' . $column . " {$op} {$step}";
            }
        }

        $set = '';
        if (!empty($update)) {
            $set .= 'SET ' . implode(', ', $update);
        }

        return $this->applyTemplate('UPDATE %table% %set% %where%', array(
            'table' =>  $this->quoteColumn($query->getQuery('table')),
            'set'   =>  $set,
            'where' =>  $this->parseWhere($query)
        ));
    }

    /**
     * parseInsert  
     * 
     * @param InsertQuery $query 
     * @access public
     * @return string
     */
    public function parseInsert(InsertQuery $query)
    {
        $values = '';

        if ($query->getQuery('values')) {
            $items = $query->getQuery('values');
            $keys = array();
            $vals = array();
            
            foreach ($items as $key => $val) {
                $keys[] = $this->quoteColumn($key);
                $vals[] = $this->quoteValue($val);
            }

            $values .= '(' . implode(', ', $keys) . ') VALUES (' . implode(', ', $vals) . ')';
        }

        return $this->applyTemplate('INSERT INTO %table% %values%', array(
            'table' =>  $this->quoteColumn($query->getQuery('table')),
            'values'=>  $values
        ));
    }

    /**
     * parseQuery  
     * 
     * @param SqlQuery $query 
     * @access public
     * @return string
     */
    public function parseQuery(SqlQuery $query)
    {
        return $query->getQuery('query');
    }
}

