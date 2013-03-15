<?php

namespace TE\Db\Adapter\Pdo;

/**
 * SQLite
 * 
 * @uses AbstractPdoAdapter
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class SQLite extends AbstractPdoAdapter
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

