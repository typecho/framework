<?php

namespace TE\Validator;

/**
 * AbstractFilledValidator  
 * 
 * @uses FilledValidatorInterface
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
abstract class AbstractFilledValidator implements FilledValidatorInterface
{
    /**
     * _data  
     * 
     * @var mixed
     * @access private
     */
    private $_data;

    /**
     * validate  
     * 
     * @param array $data 
     * @param mixed $key 
     * @param array $args 
     * @access public
     * @return void
     */
    public function validate(array $data, $key, array $args)
    {
        $this->_data = $data;
        array_unshift($args, $data[$key]);

        if (method_exists($this, 'validateCallback')) {
            return call_user_func_array(array($this, 'validateCallback'), $args);
        }

        return true;
    }

    /**
     * getData  
     * 
     * @param mixed $key 
     * @access public
     * @return void
     */
    public function getData($key = NULL)
    {
        return empty($key) ? $this->_data : 
            (isset($this->_data[$key]) ? $this->_data[$key] : NULL);
    }
}

