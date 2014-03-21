<?php

require_once 'common.php';

class Foo extends \TE\Mvc\Controller\AbstractController
{
    public function saySomething($say)
    {
        return array('content', $say);
    }
}

new \TE\Mvc\Server(array(
    'get:/?say'     =>      'Foo#saySomething'
));

