<?php

namespace TE\Validator;

/**
 * LessThan  
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class LessThan extends AbstractFilledValidator
{
    /**
     * validateCallback 
     * 
     * @param mixed $number 
     * @param mixed $less 
     * @param boolean $le
     * @access public
     * @return boolean
     */
    public function validateCallback($number, $less, $le = false)
    {
        return $le ? $number <= $less : $number < $less;
    }
}

