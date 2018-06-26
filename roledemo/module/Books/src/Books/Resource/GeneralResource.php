<?php
namespace Books\Resource;

use Doctrine\Instantiator\InstantiatorInterface;
use ZF\Apigility\Doctrine\Server\Resource\DoctrineResource;
use ZF\Apigility\Doctrine\Server\Event\DoctrineResourceEvent;
use ZF\ApiProblem\ApiProblem;

abstract class GeneralResource extends DoctrineResource
{
    /**
     * @var InstantiatorInterface|null
     */
    protected $entityFactory;

    /**
     * Create a resource
     *
     * @param mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $entityClass = $this->getEntityClass();

        $data = $this->getQueryCreateFilter()->filter($this->getEvent(), $entityClass, $data);

        if ($data instanceof ApiProblem) {
            return $data;
        }

        $entity = $this->entityFactory
            ? $this->entityFactory->instantiate($entityClass)
            : new $entityClass();

        $results = $this->triggerDoctrineEvent(DoctrineResourceEvent::EVENT_CREATE_PRE, $entity, $data);

        if ($results->last() instanceof ApiProblem) {
            return $results->last();
        } elseif (! $results->isEmpty() && $results->last() !== null) {
            // TODO Change to a more logical/secure way to see if data was acted and and we have the expected response
            $preEventData = $results->last();
        } else {
            $preEventData = $data;
        }

        $hydrator = $this->getHydrator();
        $hydrator->hydrate((array) $preEventData, $entity);

        // Sets relations in child class
        $this->setRelations($entity);

        $this->getObjectManager()->persist($entity);

        $results = $this->triggerDoctrineEvent(DoctrineResourceEvent::EVENT_CREATE_POST, $entity, $data);
        if ($results->last() instanceof ApiProblem) {
            return $results->last();
        }

        $this->getObjectManager()->flush();

        return $entity;
    }

    /**
     * Sets entity's relations according to foreign keys if present
     *
     * @param  object  $entity
     */
    abstract function setRelations(&$entity);
}