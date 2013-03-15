<?php

namespace TE\Mvc\Action;

/**
 * ExeptionAction
 * 
 * @uses AbstractAction
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class ExeptionAction extends AbstractAction
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
     * execute  
     * 
     * @access public
     * @return void
     */
    public function execute()
    {
        $lines = array_filter(explode("\n", $this->_exception->getTraceAsString()));
        $result = array();
        foreach ($lines as $line) {
            $line = trim($line);

            if (strpos($line, ':')) {
                list ($prefix, $func) = explode(':', $line);
                list ($num, $file) = explode(' ', $prefix, 2);
                $func = str_replace(array('&lt;?php&nbsp;&nbsp;', "\n"), '', highlight_string('<?php ' . $func, true));

                $result[] = "{$num} <cite>$file</cite> {$func}";
            } else {
                $result[] = $line;
            }
        }

        $h = '<p style="background: #fee; border: 1px solid #660; color: #600; padding: .5em"><strong>' . get_class($this->_exception) . '</strong>: [' 
            . $this->_exception->getCode() . '] ' . $this->_exception->getMessage() . '</p>';

        $trace = '<pre style="background: #fff; border: 1px solid #000; padding: 0 .5em; overflow: auto; display: block"><p>' 
            . implode('</p><p>', $result) . '</p></pre>';

        $body = '<html><head><title>Exception</title><style>code {background: #e1e1e1; border: 1px solid #000; padding: .1em; 
            overflow: auto} cite { font-style: normal; font-weight: bold; font-size: .9em }</style></head>'
            . '<body style="background: #eeeeff; font-family: verdana, arial, helvetica, sans-serif">' . $h . $trace . '</body></html>';

        return array('content', $body);
    }

    /**
     * setContent
     * 
     * @param mixed $content
     * @access public
     * @return void
     */
    public function setContent($content)
    {
        $this->_content = $content;
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
}

