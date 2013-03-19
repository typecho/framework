<?php

namespace TE\Validator;

/**
 * ValidatorExecutor  
 * 
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class ValidatorExecutor
{
    /**
     * _data  
     * 
     * @var mixed
     * @access private
     */
    private $_data;

    /**
     * _rules  
     * 
     * @var mixed
     * @access private
     */
    private $_rules;

    /**
     * _validatorClasses 
     * 
     * @var array
     * @access private
     */
    private $_validatorClasses = array(
        'between'       =>  'TE\Validator\Between',
        'birthday'      =>  'TE\Validator\Birthday',
        'confirm'       =>  'TE\Validator\Confirm',
        'email'         =>  'TE\Validator\Email',
        'greaterThan'   =>  'TE\Validator\GreaterThan',
        'inArray'       =>  'TE\Validator\InArray',
        'lessThan'      =>  'TE\Validator\LessThan',
        'digit'         =>  'TE\Validator\Digit',
        'regex'         =>  'TE\Validator\Regex',
        'required'      =>  'TE\Validator\Required',
        'maxStrLen'     =>  'TE\Validator\MaxStrLen',
        'minStrLen'     =>  'TE\Validator\MinStrLen',
        'url'           =>  'TE\Validator\Url'
    );

    /**
     * _validatorObjects  
     * 
     * @var array
     * @access private
     */
    private $_validatorObjects = array(); 

    /**
     * __construct  
     * 
     * @param array $data 
     * @param array $rules 
     * @access public
     * @return void
     */
    public function __construct(array $data, array $rules)
    {
        $this->_data = $data;
        $this->_rules = $rules;
    }

    /**
     * getValidator  
     * 
     * @param mixed $name 
     * @access private
     * @return void
     */
    private function getValidator($name)
    {
        if (!isset($this->_validatorObjects[$name])) {
            $className = isset($this->_validatorClasses[$name]) ? $this->_validatorClasses[$name] : $name;
            $this->_validatorObjects[$name] = new $className();
        }

        return $this->_validatorObjects[$name];
    }

    /**
     * runValidator 
     * 
     * @param ValidatorInterface $validator 
     * @param mixed $key 
     * @param array $args 
     * @access private
     * @return void
     */
    private function runValidator(ValidatorInterface $validator, $key, array $args)
    {
        return $validator->validate($this->_data, $key, $args);
    }

    /**
     * validate  
     * 
     * @access public
     * @return void
     */
    public function validate()
    {
        $result = array();

        foreach ($this->_rules as $key => $rules) {
            foreach ($rules as $rule) {
                list ($args, $message) = $rule;

                if (is_array($args)) {
                    $name = array_shift($args);
                } else {
                    $name = $args;
                    $args = array();
                }
                
                $validator = $this->getValidator($name);
                if ($validator instanceof FilledValidatorInterface
                    && empty($this->_data[$key])) {
                    continue;    
                }

                if (!$this->runValidator($validator, $key, $args)) {
                    $result[$key] = $message;
                    break;
                }
            }
        }

        return $result;
    }
}

