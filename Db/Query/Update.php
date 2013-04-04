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
     * @return void
     */
    public function set($column, $value)
    {
        $this->pushQuery('set', array($this->applyPrefix($column), $value));
    }

    /**
     * setMulti  
     * 
     * @param array $rows 
     * @access public
     * @return void
     */
    public function setMulti(array $rows)
    {
        $this->pushQuery('mset', $this->applyPrefix($rows));
    }

    /**
     * incrBy
     *
     * @param $column
     * @param $step
     */
    public function incrBy($column, $step)
    {
        $this->pushQuery('xset', array('+', $this->applyPrefix($column), intval($step)));
    }

    /**
     * decrBy  
     * 
     * @param mixed $column 
     * @param mixed $step 
     * @access public
     * @return void
     */
    public function decrBy($column, $step)
    {
        $this->pushQuery('xset', array('-', $this->applyPrefix($column), intval($step)));
    }

    /**
     * incr  
     * 
     * @param mixed $column 
     * @access public
     * @return void
     */
    public function incr($column)
    {
        $this->incrBy($column, 1);
    }

    /**
     * decr  
     * 
     * @param mixed $column 
     * @access public
     * @return void
     */
    public function decr($column)
    {
        $this->decrBy($column, 1);
    }
}

