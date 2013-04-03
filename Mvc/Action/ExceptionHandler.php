<?php

namespace TE\Mvc\Action;

/**
 * ExceptionHandler
 * 
 * @uses AbstractAction
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class ExceptionHandler extends AbstractAction
{
    /**
     * _content
     * 
     * @var mixed
     * @access private
     */
    private $_content;

    /**
     * _exception  
     * 
     * @var mixed
     * @access private
     */
    private $_exception;

    /**
     * _template  
     * 
     * @var mixed
     * @access private
     */
    private $_template;

    /**
     * execute  
     * 
     * @access public
     * @return mixed
     */
    public function execute()
    {
        return array('error', $this->_exception, $this->_template);
    }

    /**
     * setException  
     * 
     * @param \Exception $e 
     * @access public
     * @return void
     */
    public function setException(\Exception $e)
    {
        $this->_exception = $e;
    }

    /**
     * setTemplate  
     * 
     * @param mixed $template 
     * @access public
     * @return void
     */
    public function setTemplate($template)
    {
        $this->_template = $template;
    }
}

