<?php
namespace Books\V1\Rest\Book;

class BookResourceFactory
{
    public function __invoke($services)
    {
        return new BookResource();
    }
}
