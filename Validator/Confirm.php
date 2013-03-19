<?php

namespace TE\Validator;

/**
 * Confirm  
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Confirm extends AbstractFilledValidator
{
    /**
     * validateCallback 
     * 
     * @param mixed $input 
     * @param mixed $confirm 
     * @access public
     * @return void
     */
    public function validateCallback($input, $confirm)
    {
        return $input == $this->getData($confirm);
    }
}

