<?php

namespace TE\Validator;

/**
 * MaxStrLen  
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class MaxStrLen extends AbstractFilledValidator
{
    /**
     * validateCallback  
     * 
     * @param mixed $str 
     * @param mixed $max 
     * @param mixed $me 
     * @access public
     * @return void
     */
    public function validateCallback($str, $max, $me = false)
    {
        $len = strlen($str);
        return $me ? $len <= $max : $len < $max;
    }
}

