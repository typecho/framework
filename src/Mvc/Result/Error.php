<?php

namespace TE\Mvc\Result;

use TE\Mvc\Response;

/**
 * Error 
 * 
 * @uses AbstractResult
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Error extends Template
{
    /**
     * @var string
     */
    private $_template = '';

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->_template = $template;
    }

    /**
     * init some vars
     */
    public function init()
    {
        $this->getEvent()->setData('content', $this->getParam(0, 'Error'));
        $this->setParam(0, $this->_template);
    }

    /**
     * prepareResponse  
     * 
     * @param Response $response
     * @access public
     * @return void
     */
    public function prepareResponse(Response $response)
    {
        $response->setStatusCode(500);
    }
}

