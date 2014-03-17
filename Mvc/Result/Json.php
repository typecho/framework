<?php

namespace TE\Mvc\Result;

use TE\Mvc\Server\ResponseInterface as Response;
use TE\Mvc\Action\ActionEvent as Event;

/**
 * Json  
 * 
 * @uses AbstractResult
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Json extends AbstractResult
{
    /**
     * _data
     * 
     * @var mixed
     * @access private
     */
    private $_data;

    /**
     * @param Event $event
     * @param       $data   json数据
     */
    public function __construct(Event $event, $data)
    {
        $this->_data = $data;
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
        $response->setStatusCode(200)
            ->setHeader('Cache-Control', 'no-cache')
            ->setContentType('application/json');
    }

    /**
     * render  
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        echo json_encode($this->_data);
        exit;
    }
}

