<?php

namespace TE\Validator;

/**
 * Digit
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Digit extends AbstractFilledValidator
{
    /**
     * validateCallback  
     * 
     * @param mixed $number 
     * @access public
     * @return boolean
     */
    public function validateCallback($number)
    {
        return ctype_digit($number);
    }
}

