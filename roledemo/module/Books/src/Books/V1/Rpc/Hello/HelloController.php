<?php
namespace Books\V1\Rpc\Hello;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;

class HelloController extends AbstractActionController
{
    public function helloAction()
    {
        return new ViewModel([
            'ack' => time(),
        ]);
    }
}
