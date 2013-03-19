<?php

namespace TE\Validator;

/**
 * Birthday  
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Birthday extends AbstractFilledValidator
{
    /**
     * validateCallback 
     * 
     * @param mixed $date 
     * @param mixed $format 
     * @access public
     * @return void
     */
    public function validateCallback($date, $format = '%Y-%m-%d')
    {
        $year = date('Y');
        $parsed = strptime($date, $format);

        if (false === $parsed) {
            return false;
        } else if ($parsed['tm_year'] < 0 || !empty($parsed['unparsed']) || $parsed['tm_year'] + 1900 >= $year) {
            return false;
        }

        return true;
    }
}

