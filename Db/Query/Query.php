<?php

namespace TE\Db\Query;

/**
 * Query
 * 
 * @uses AbstractQuery
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Query extends AbstractQuery
{
    /**
     * init  
     * 
     * @param mixed $query
     * @access public
     * @return void
     */
    public function init($query)
    {
        $this->setQuery('query', $query);
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
}

