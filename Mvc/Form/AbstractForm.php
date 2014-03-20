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
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

        // get public field
        foreach ($props as $prop) {
            $name = $prop->getName();
            $default = $prop->getValue($this);
            $setterName = 'set' . ucfirst($name);
            $this->_props[] = $name;

            if ($reflect->hasMethod($setterName)) {
                $method = $reflect->getMethod($setterName);

                if ($method->isPublic()) {
                    $params = $method->getParameters();
                    if (1 == count($params)) {
                        $default = $params[0]->isDefaultValueAvailable()
                            ? $params[0]->getDefaultValue() : $default;

                        if ($params[0]->isArray()) {
                            $value = $request->getArray($name);
                            if (empty($value) && is_array($default)) {
                                $value = $default;
                            }
                        } else {
                            $default = is_array($default) ? NULL : $default;
                            $value = $request->get($name, $default);
                            $value = is_array($value) ? $default : $value;
                        }

                        $this->_current = $value;

                        try {
                            $this->{$setterName}($value);
                        } catch (ValidateException $e) {
                            $this->_messages[$name] = $e->getMessage();
                            $prop->setValue($this, $value);
                            $this->_isValid = false;
                        }

                        continue;
                    }
                }
            }

            if (is_array($default)) {
                $value = $request->getArray($name);
            } else {
                $value = $request->get($name, $default);
                if (is_array($value)) {
                    $value = NULL;
                }
            }

            $prop->setValue($this, $value);
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
                case 'digit':
                    $this->checkValid(ctype_digit($this->_current), $message);
                    break;
                case 'confirm':
                    list ($name) = $this->requireArgs($args, 1);
                    $this->checkValid($this->_current == $this->{$name}, $message);
                    break;
                case 'required':
                    $this->checkValid(strlen($this->_current) > 0, $message);
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