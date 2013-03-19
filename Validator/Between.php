<?php

namespace TE\Validator;

/**
 * Between 
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Between extends AbstractFilledValidator
{
    /**
     * validateCallback  
     * 
     * @param mixed $number 
     * @param mixed $greater 
     * @param mixed $less 
     * @param mixed $ge 
     * @param mixed $le 
     * @access public
     * @return void
     */
    public function validateCallback($number, $greater, $less, $ge = false, $le = false)
    {
        return ($ge ? $number >= $greater : $number > $greater)
            && ($le ? $number <= $less : $number < $less);
    }
}

