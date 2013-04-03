<?php

namespace TE\Validator;

/**
 * Required  
 * 
 * @uses ValidatorInterface
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Required implements ValidatorInterface
{
    /**
     * validate  
     * 
     * @param array $data 
     * @param mixed $key 
     * @param array $args 
     * @access public
     * @return boolean
     */
    public function validate(array $data, $key, array $args)
    {
        return !empty($data[$key]);
    }
}

