<?php

namespace TE\Validator;

/**
 * Email  
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Email extends AbstractFilledValidator
{
    /**
     * validateCallback  
     * 
     * @param mixed $email 
     * @access public
     * @return boolean
     */
    public function validateCallback($email)
    {
        return preg_match("/^[_a-z0-9-\.]+@[^@]+\.[a-z]{2,}$/i", $email);
    }
}

