<?php

require_once 'common.php';

class Foo extends \TE\Mvc\Controller\AbstractController
{
    public function execute()
    {
        return array('content', 'Hello World!');
    }
}

new \TE\Mvc\Server(array(
    'get:/'     =>      'Foo'
));

