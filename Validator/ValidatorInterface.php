<?php

namespace TE\Validator;

use TE\Mvc\Server\RequestInterface as Request;

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
     * @param array $args 
     * @param array $data 
     * @access public
     * @return void
     */
    public function validate(array $args, array $data);
}

