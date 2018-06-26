<?php
namespace Books\V1\Rest\Author;

class AuthorResourceFactory
{
    public function __invoke($services)
    {
        return new AuthorResource();
    }
}
