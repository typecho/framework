<?php

namespace TE\Db\Adapter\Pdo;

/**
 * Pgsql 
 * 
 * @uses AbstractPdoAdapter
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Pgsql extends AbstractPdoAdapter
{
    /**
     * 对象引号过滤
     *
     * @access public
     * @param string $string
     * @return string
     */
    public function quoteColumn($string)
    {
        return '"' . $string . '"';
    }
}

