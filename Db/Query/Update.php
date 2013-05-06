<?php

namespace TE\Db\Query;

/**
 * Update  
 * 
 * @uses AbstractQuery
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Update extends AbstractQuery
{
    /**
     * init  
     * 
     * @param mixed $table 
     * @access public
     * @return void
     */
    public function init($table)
    {
        $this->setQuery('table', $this->applyPrefix($table));
    }

    /**
     * set  
     * 
     * @param string $column
     * @param mixed $value 
     * @access public
     * @return Update
     */
    public function set($column, $value)
    {
        if (is_array($value)) {
            list ($op, $step) = $value;
            if ('+' == $op) {
                $this->incrBy($column, $step);
            } else {
                $this->decrBy($column, $step);
            }
        } else {
            $this->pushQuery('set', array($this->applyPrefix($column), $value));
        }
        return $this;
    }

    /**
     * setMulti  
     * 
     * @param array $rows 
     * @access public
     * @return Update
     */
    public function setMultiple(array $rows)
    {
        foreach ($rows as $column => $value) {
            $this->set($column, $value);
        }

        return $this;
    }

    /**
     * incrBy
     *
     * @param $column
     * @param $step
     * @return Update
     */
    public function incrBy($column, $step)
    {
        $this->pushQuery('xset', array('+', $this->applyPrefix($column), intval($step)));
        return $this;
    }

    /**
     * decrBy  
     * 
     * @param mixed $column 
     * @param mixed $step 
     * @return Update
     */
    public function decrBy($column, $step)
    {
        $this->pushQuery('xset', array('-', $this->applyPrefix($column), intval($step)));
        return $this;
    }

    /**
     * incr
     *
     * @param $column
     * @return Update
     */
    public function incr($column)
    {
        return $this->incrBy($column, 1);
    }

    /**
     * decr
     *
     * @param $column
     * @return Update
     */
    public function decr($column)
    {
        return $this->decrBy($column, 1);
    }

    /**
     * exec
     *
     * @return int
     */
    public function exec()
    {
        $handle = $this->getAdapter()->query((string) $this);
        return $this->getAdapter()->affectedRows($handle);
    }
}

