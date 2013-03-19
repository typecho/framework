<?php

namespace TE\Validator;

/**
 * Regex  
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Regex extends AbstractFilledValidator
{
    /**
     * validateCallback  
     * 
     * @param mixed $input 
     * @param mixed $regex 
     * @access public
     * @return void
     */
    public function validateCallback($input, $regex)
    {
        return preg_match($regex, $input);
    }
}

