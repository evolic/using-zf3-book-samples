<?php
namespace Books\V1\Rpc\Hello;

class HelloControllerFactory
{
    public function __invoke($controllers)
    {
        return new HelloController();
    }
}
