<?php

namespace TE\Validator;

/**
 * MinStrLen  
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class MinStrLen extends AbstractFilledValidator
{
    /**
     * validateCallback  
     * 
     * @param mixed $str 
     * @param mixed $min 
     * @param mixed $me 
     * @access public
     * @return void
     */
    public function validateCallback($str, $min, $me = false)
    {
        $len = strlen($str);
        return $me ? $len <= $min : $len < $min;
    }
}

