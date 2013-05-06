<?php

namespace TE\Db\Query;

/**
 * Delete
 * 
 * @uses AbstractQuery
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Delete extends AbstractQuery
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

