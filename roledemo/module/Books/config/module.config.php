<?php
use Zend\ServiceManager\Factory\InvokableFactory;

return array(
    'doctrine' => array(
        'driver' => array(
            'my_annotation_driver' => array(
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    0 => __DIR__ . '/../src/Books/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Books\\Entity' => 'my_annotation_driver',
                ),
            ),
        ),
    ),
    'doctrine-hydrator' => array(
        'Books\\V1\\Rest\\Author\\AuthorHydrator' => array(
            'entity_class' => 'Books\\Entity\\Author',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => true,
            'strategies' => [
                'birth_date'    => 'Books\V1\Hydrator\Strategy\DateStrategy',
                'death_date'    => 'Books\V1\Hydrator\Strategy\DateStrategy',
                'books'         => 'ZF\Doctrine\Hydrator\Strategy\CollectionExtract',
            ],
            'use_generated_hydrator' => true,
        ),
        'Books\\V1\\Rest\\Book\\BookHydrator' => array(
            'entity_class' => 'Books\\Entity\\Book',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => true,
            'strategies' => [
                'author' => 'ZF\Doctrine\Hydrator\Strategy\EntityLink',
            ],
            'use_generated_hydrator' => true,
        ),
        'Books\\V1\\Rest\\AuthorBook\\BookHydrator' => array(
            'entity_class' => 'Books\\Entity\\Book',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => true,
            'strategies' => [
                'author' => 'ZF\Doctrine\Hydrator\Strategy\EntityLink',
            ],
            'use_generated_hydrator' => true,
        ),
    ),
    'hydrators' => [
        'factories' => [
        ],
    ],
    'service_manager' => array(
        'factories' => [
            'Books\V1\Hydrator\Strategy\DateStrategy' => InvokableFactory::class,
        ],
    ),
    'router' => array(
        'routes' => array(
            'books.rest.doctrine.author' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/authors[/:author_id]',
                    'defaults' => array(
                        'controller' => 'Books\\V1\\Rest\\Author\\Controller',
                    ),
                ),
            ),
            'books.rest.doctrine.book' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/books[/:book_id]',
                    'defaults' => array(
                        'controller' => 'Books\\V1\\Rest\\Book\\Controller',
                    ),
                ),
            ),
            'books.rest.doctrine.author-book' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/authors/:author_id/books[/:book_id]',
                    'defaults' => array(
                        'controller' => 'Books\\V1\\Rest\\AuthorBook\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'books.rest.doctrine.author',
            1 => 'books.rest.doctrine.book',
            2 => 'books.rest.doctrine.author-book',
        ),
    ),
    'zf-rest' => array(
        'Books\\V1\\Rest\\Author\\Controller' => array(
            'listener' => 'Books\\V1\\Rest\\Author\\AuthorResource',
            'route_name' => 'books.rest.doctrine.author',
            'route_identifier_name' => 'author_id',
            'entity_identifier_name' => 'id',
            'collection_name' => 'authors',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => [
                'first_name',
                'last_name',
                'alias',
            ],
            'page_size' => '5',
            'page_size_param' => null,
            'entity_class' => 'Books\\Entity\\Author',
            'collection_class' => 'Books\\V1\\Rest\\Author\\AuthorCollection',
            'service_name' => 'Author',
        ),
        'Books\\V1\\Rest\\Book\\Controller' => array(
            'listener' => 'Books\\V1\\Rest\\Book\\BookResource',
            'route_name' => 'books.rest.doctrine.book',
            'route_identifier_name' => 'book_id',
            'entity_identifier_name' => 'id',
            'collection_name' => 'books',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => [
                'title',
                'author_id',
            ],
            'page_size' => '5',
            'page_size_param' => null,
            'entity_class' => 'Books\\Entity\\Book',
            'collection_class' => 'Books\\V1\\Rest\\Book\\BookCollection',
            'service_name' => 'Book',
        ),
        'Books\\V1\\Rest\\AuthorBook\\Controller' => array(
            'listener' => 'Books\\V1\\Rest\\AuthorBook\\BookResource',
            'route_name' => 'books.rest.doctrine.author-book',
            'route_identifier_name' => 'book_id',
            'entity_identifier_name' => 'id',
            'collection_name' => 'books',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => [
                'title',
            ],
            'page_size' => '5',
            'page_size_param' => null,
            'entity_class' => 'Books\\Entity\\Book',
            'collection_class' => 'Books\\V1\\Rest\\AuthorBook\\BookCollection',
            'service_name' => 'AuthorBook',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Books\\V1\\Rest\\Author\\Controller' => 'HalJson',
            'Books\\V1\\Rest\\Book\\Controller' => 'HalJson',
            'Books\\V1\\Rest\\AuthorBook\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'Books\\V1\\Rest\\Author\\Controller' => array(
                0 => 'application/vnd.books.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Books\\V1\\Rest\\Book\\Controller' => array(
                0 => 'application/vnd.books.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Books\\V1\\Rest\\AuthorBook\\Controller' => array(
                0 => 'application/vnd.books.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Books\\V1\\Rest\\Author\\Controller' => array(
                0 => 'application/vnd.books.v1+json',
                1 => 'application/json',
            ),
            'Books\\V1\\Rest\\Book\\Controller' => array(
                0 => 'application/vnd.books.v1+json',
                1 => 'application/json',
            ),
            'Books\\V1\\Rest\\AuthorBook\\Controller' => array(
                0 => 'application/vnd.books.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Books\\Entity\\Author' => array(
                'route_identifier_name' => 'author_id',
                'entity_identifier_name' => 'id',
                'route_name' => 'books.rest.doctrine.author',
                'hydrator' => 'Books\\V1\\Rest\\Author\\AuthorHydrator',
            ),
            'Books\\V1\\Rest\\Author\\AuthorCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'books.rest.doctrine.author',
                'is_collection' => true,
            ),
            'Books\\Entity\\Book' => array(
                'route_identifier_name' => 'book_id',
                'entity_identifier_name' => 'id',
                'route_name' => 'books.rest.doctrine.book',
                'hydrator' => 'Books\\V1\\Rest\\Book\\BookHydrator',
            ),
            'Books\\V1\\Rest\\Book\\BookCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'books.rest.doctrine.book',
                'is_collection' => true,
            ),
            'Books\\Entity\\AuthorBook' => array(
                'route_identifier_name' => 'book_id',
                'entity_identifier_name' => 'id',
                'route_name' => 'books.rest.doctrine.author-book',
                'hydrator' => 'Books\\V1\\Rest\\AuthorBook\\BookHydrator',
            ),
            'Books\\V1\\Rest\\AuthorBook\\BookCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'books.rest.doctrine.author-book',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-apigility' => array(
        'doctrine-connected' => array(
            'Books\\V1\\Rest\\Author\\AuthorResource' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'hydrator' => 'Books\\V1\\Rest\\Author\\AuthorHydrator',
            ),
            'Books\\V1\\Rest\\Book\\BookResource' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'hydrator' => 'Books\\V1\\Rest\\Book\\BookHydrator',
            ),
            'Books\\V1\\Rest\\AuthorBook\\BookResource' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'hydrator' => 'Books\\V1\\Rest\\AuthorBook\\BookHydrator',
            ),
        ),
    ),
    'zf-content-validation' => array(
        'Books\\V1\\Rest\\Author\\Controller' => array(
            'input_filter' => 'Books\\V1\\Rest\\Author\\Validator',
        ),
        'Books\\V1\\Rest\\Book\\Controller' => array(
            'input_filter' => 'Books\\V1\\Rest\\Book\\Validator',
        ),
        'Books\\V1\\Rest\\AuthorBook\\Controller' => array(
            'input_filter' => 'Books\\V1\\Rest\\Book\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'Books\\V1\\Rest\\Author\\Validator' => array(
            0 => array(
                'name' => 'first_name',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 56,
                        ),
                    ),
                ),
            ),
            1 => array(
                'name' => 'last_name',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 56,
                        ),
                    ),
                ),
            ),
            2 => array(
                'name' => 'birth_date',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Date',
                        'options' => array(),
                    ),
                ),
            ),
            3 => array(
                'name' => 'death_date',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Date',
                        'options' => array(),
                    ),
                ),
                'allow_empty' => true,
            ),
        ),
        'Books\\V1\\Rest\\Book\\Validator' => array(
            0 => array(
                'name' => 'title',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 56,
                        ),
                    ),
                ),
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Digits',
                        'options' => array(),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\ToInt',
                        'options' => array(),
                    ),
                ),
                'name' => 'author_id',
                'field_type' => 'int',
            ),
        ),
    ),
);
