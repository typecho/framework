<?php

namespace TE\Validator;

/**
 * Url 
 * 
 * @uses AbstractFilledValidator
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Url extends AbstractFilledValidator
{
    /**
     * validateCallback  
     * 
     * @param mixed $email 
     * @access public
     * @return void
     */
    public function validateCallback($url)
    {
        return preg_match("/^(http|https):\/\/.+$/i", $url);
    }
}

