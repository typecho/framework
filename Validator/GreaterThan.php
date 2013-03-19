<?php

namespace TE\Validator;

/**
 * GreaterThan  
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class GreaterThan extends AbstractFilledValidator
{
    /**
     * validateCallback  
     * 
     * @param mixed $number
     * @param mixed $greater 
     * @param mixed $ge 
     * @access public
     * @return void
     */
    public function validateCallback($number, $greater, $ge = false)
    {
        return $ge ? $number >= $greater : $number > $greater;
    }
}

