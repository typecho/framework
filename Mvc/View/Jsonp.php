<?php

namespace TE\Mvc\View;

use TE\Mvc\Server\Http\Response;
use TE\Mvc\Action\ActionEvent as Event;

/**
 * Jsonp
 * 
 * @uses AbstractView
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General Public License 2.0
 */
class Jsonp extends AbstractView
{
    /**
     * _data
     * 
     * @var mixed
     * @access private
     */
    private $_data;

    /**
     * _callback  
     * 
     * @var mixed
     * @access private
     */
    private $_callback;

    /**
     * @param Event  $event
     * @param        $data      jsonp的数据
     * @param string $callback  jsonp的回调函数名
     */
    public function __construct(Event $event, $data, $callback = 'callback')
    {
        $this->_data = $data;
        $this->_callback = $event->getAction()->getRequest()->get($callback, 'jsonp');
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
            ->setContentType('text/javascript');
    }

    /**
     * render  
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        echo $this->_callback . '(' . json_encode($this->_data) . ')';
        exit;
    }
}

