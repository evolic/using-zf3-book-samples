<?php
namespace Books\V1\Rest\AuthorBook;

class BookResourceFactory
{
    public function __invoke($services)
    {
        return new BookResource();
    }
}
