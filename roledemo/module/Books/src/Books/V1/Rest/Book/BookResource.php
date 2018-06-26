<?php
namespace Books\V1\Rest\Book;

use Books\Entity\Author;
use Books\Entity\Book;
use Books\Resource\GeneralResource;

class BookResource extends GeneralResource
{
    /**
     * @param  Book  $entity
     */
    protected function setRelations(&$entity)
    {
        /** @var Book $entity */
        /** @var Author $author */
        $author = $this->getObjectManager()->getRepository(Author::class)->find($entity->getAuthorId());

        $entity->setAuthor($author);
    }
}
