<?php

namespace TE\Mvc\Form;

use TE\Base;
use TE\Mvc\Request;

/**
 * Class AbstractForm
 * @package TE\Mvc\Form
 */
class AbstractForm extends Base
{
    /**
     * @var mixed
     */
    private $_current;

    /**
     * @var array
     */
    private $_messages = array();

    /**
     * @var bool
     */
    private $_isValid = true;

    /**
     * @var array
     */
    private $_props = array();

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

        // get public field
        foreach ($props as $prop) {
            $name = $prop->getName();
            $setterName = 'set' . ucfirst($name);
            $this->_props[] = $name;

            // get value by default define
            $default = $prop->getValue($this);
            if (is_array($default)) {
                $value = $request->getArray($name);
                if (empty($value)) {
                    $value = $default;
                }
            } else {
                $value = $request->get($name, $default);
                if (is_array($value)) {
                    $value = $default;
                }
            }

            // set value to current var
            $this->_current = $value;

            if ($reflect->hasMethod($setterName)) {
                $method = $reflect->getMethod($setterName);

                if ($method->isPublic()) {
                    $params = $method->getParameters();
                    if (1 == count($params)) {
                        try {
                            $this->{$setterName}($value);
                            continue;
                        } catch (ValidateException $e) {
                            $this->_messages[$name] = $e->getMessage();
                            $prop->setValue($this, $value);
                            $this->_isValid = false;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param array $args
     * @param $num
     * @return array
     * @throws ValidateException
     */
    private function requireArgs(array $args, $num)
    {
        if (count($args) != $num) {
            throw new ValidateException('Validator require ' . $num . ' params');
        }

        return $args;
    }

    /**
     * @param boolean $condition
     * @param $message
     * @return $this
     * @throws ValidateException
     */
    private function checkValid($condition, $message)
    {
        if (!$condition) {
            throw new ValidateException($message);
        }

        return $this;
    }

    /**
     * @param $condition
     * @param $message
     * @return $this
     */
    protected function validate($condition, $message)
    {
        $params = $condition;

        if (is_string($condition)) {
            parse_str($condition, $params);
        } else if (is_bool($condition)) {
            return $this->checkValid($condition, $message);
        }

        foreach ($params as $key => $val) {
            $args = explode(',', $val);

            switch ($key) {
                case 'url':
                    $this->checkValid(preg_match("/^(http|https):\/\/.+$/i", $this->_current), $message);
                    break;
                case 'mail':
                    $this->checkValid(preg_match("/^[_a-z0-9-\.]+@[^@]+\.[a-z]{2,}$/i", $this->_current), $message);
                    break;
                case 'type':
                    list ($type) = $this->requireArgs($args, 1);
                    if (!in_array($type, array('alnum', 'alpha', 'cntrl', 'digit', 'graph', 'lower', 'print',
                        'punct', 'space', 'upper', 'xdigit'))) {
                        break;
                    }

                    $method = 'ctype_' . $type;
                    $this->checkValid($method($this->_current), $message);
                    break;
                case 'confirm':
                    list ($name) = $this->requireArgs($args, 1);
                    $this->checkValid($this->_current == $this->{$name}, $message);
                    break;
                case 'required':
                    $this->checkValid(is_array($this->_current) ? !empty($this->_current) :
                        strlen($this->_current) > 0, $message);
                    break;
                default:
                    break;
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->_isValid;
    }

    /**
     * getMessages  
     * 
     * @access public
     * @return varray
     */
    public function getMessages()
    {
        return $this->_messages;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach ($this->_props as $name) {
            $result[$name] = $this->{$name};
        }

        return $result;
    }
}
