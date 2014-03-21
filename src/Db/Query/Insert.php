<?php

namespace TE\Db\Query;

/**
 * Insert  
 * 
 * @uses AbstractQuery
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Insert extends AbstractQuery
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
     * where  
     * 
     * @access public
     * @return Insert
     */
    public function where()
    {
        return $this;
    }
    
    /**
     * orWhere  
     * 
     * @access public
     * @return Insert
     */
    public function orWhere()
    {
        return $this;
    }

    /**
     * values  
     * 
     * @param array $values 
     * @access public
     * @return Insert
     */
    public function values(array $values)
    {
        $this->setQuery('values', $this->applyPrefix($values));
        return $this;
    }

    /**
     * exec
     *
     * @return int
     */
    public function exec()
    {
        $handle = $this->getAdapter()->query((string) $this);
        return $this->getAdapter()->lastInsertId($handle);
    }
}

