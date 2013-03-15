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
     * @param mixed $condition 
     * @access public
     * @return void
     */
    public function where($condition)
    {}
    
    /**
     * orWhere  
     * 
     * @param mixed $condition 
     * @access public
     * @return void
     */
    public function orWhere($condition)
    {}

    /**
     * values  
     * 
     * @param array $values 
     * @access public
     * @return void
     */
    public function values(array $values)
    {
        $this->setQuery('values', $this->applyPrefix($values));
    }
}

