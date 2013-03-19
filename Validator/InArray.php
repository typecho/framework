<?php

namespace TE\Validator;

/**
 * InArray  
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class InArray extends AbstractFilledValidator
{
    /**
     * validateCallback  
     * 
     * @param mixed $input 
     * @param array $array 
     * @access public
     * @return void
     */
    public function validateCallback($input, array $array)
    {
        return in_array($input, $array);
    }
}

