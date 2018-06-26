<?php
namespace Books\Resource;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Instantiator\InstantiatorInterface;
use ReflectionClass;
use Zend\EventManager\EventInterface;
use Zend\Stdlib\Parameters;
use ZF\Apigility\Doctrine\Server\Event\DoctrineResourceEvent;
use ZF\Apigility\Doctrine\Server\Resource\DoctrineResource;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\RestController;

/**
 * Class GeneralResource
 *
 * @package Books\Resource
 */
abstract class GeneralResource extends DoctrineResource
{
    /**
     * Fetch all or a subset of resources
     *
     * @param array $data
     * @return ApiProblem|mixed
     */
    public function fetchAll($data = [])
    {
        // Build query
        $queryProvider = $this->getQueryProvider('fetch_all');
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $queryProvider->createQuery($this->getEvent(), $this->getEntityClass(), $data);

        if ($queryBuilder instanceof ApiProblem) {
            return $queryBuilder;
        }

        // inject custom conditions
        $this->injectQueryConditions($queryBuilder, $data);

        $response = $this->triggerDoctrineEvent(
            DoctrineResourceEvent::EVENT_FETCH_ALL_PRE,
            $this->getEntityClass(),
            $data
        );
        if ($response->last() instanceof ApiProblem) {
            return $response->last();
        }

        $adapter = $queryProvider->getPaginatedQuery($queryBuilder);
        $reflection = new ReflectionClass($this->getCollectionClass());
        $collection = $reflection->newInstance($adapter);

        $results = $this->triggerDoctrineEvent(
            DoctrineResourceEvent::EVENT_FETCH_ALL_POST,
            $this->getEntityClass(),
            $data
        );
        if ($results->last() instanceof ApiProblem) {
            return $results->last();
        }

        // Add event to set extra HAL data
        $entityClass = $this->getEntityClass();

        $this->getSharedEventManager()->attach(
            RestController::class,
            'getList.post',
            function (EventInterface $e) use ($queryProvider, $entityClass, $data) {
                /** @var \ZF\Hal\Collection $halCollection */
                $halCollection = $e->getParam('collection');
                $collection = $halCollection->getCollection();

                $collection->setItemCountPerPage($halCollection->getPageSize());
                $collection->setCurrentPageNumber($halCollection->getPage());

                $halCollection->setCollectionRouteOptions([
                    'query' => $e->getTarget()->getRequest()->getQuery()->toArray(),
                ]);
            }
        );

        return $collection;
    }

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
    protected function setRelations(&$entity)
    {
    }

    /**
     * Injects query conditions (where statements)
     *
     * @param QueryBuilder $queryBuilder
     * @param Parameters   $parameters
     */
    protected function injectQueryConditions(QueryBuilder &$queryBuilder, Parameters $parameters)
    {
        foreach ($parameters as $parameter => $value) {
            switch ($parameter) {
                case 'title':
                    $queryBuilder->andWhere('row.' . $parameter . ' like :' . $parameter)
                        ->setParameter($parameter, '%' . $value . '%');
                    break;
                default:
                    $queryBuilder->andWhere('row.' . $parameter . ' = :' . $parameter)
                        ->setParameter($parameter, $value);
            }
        }
    }
}