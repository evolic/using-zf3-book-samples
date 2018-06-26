<?php
namespace Books\V1\Rest\AuthorBook;

use Books\Entity\Author;
use Books\Entity\Book;
use Books\Resource\GeneralResource;
use Doctrine\ORM\QueryBuilder;
use Zend\Stdlib\Parameters;

/**
 * Class BookResource
 *
 * @package Books\V1\Rest\AuthorBook
 */
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

    /**
     * @inheritdoc
     */
    protected function injectQueryConditions(QueryBuilder &$queryBuilder, Parameters $parameters)
    {
        parent::injectQueryConditions($queryBuilder, $parameters);

        $authorId = $this->getEvent()->getRouteParam('author_id');

        $queryBuilder->andWhere('row.author_id = :author_id')
            ->setParameter('author_id', $authorId);
    }
}
