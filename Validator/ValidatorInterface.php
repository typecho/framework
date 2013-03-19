<?php

namespace TE\Validator;

/**
 * ValidatorInterface  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
interface ValidatorInterface
{
    /**
     * validate 
     * 
     * @param array $data 
     * @param mixed $key 
     * @param array $args 
     * @access public
     * @return void
     */
    public function validate(array $data, $key, array $args);
}

