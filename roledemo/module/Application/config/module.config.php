<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Log\Logger;
use Zend\Log\Writer as LogWriter;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use ZF\Apigility\Admin;
use ZF\Apigility\Doctrine\Admin as DoctrineAdmin;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ],
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'about' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/about',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'about',
                    ],
                ],
            ],            
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'options' => [
            // The access filter can work in 'restrictive' (recommended) or 'permissive' mode.
            //
            // In restrictive mode all controller actions must be explicitly listed
            // under the 'access_filter' config key, and access is denied to any not listed 
            // action for not logged in users.
            //
            // In permissive mode, if an action is not listed under the 'access_filter' key,
            // access to it is permitted to anyone (even for not logged in users.
            // Restrictive mode is more secure and recommended to use.
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                // Allow anyone to visit "index" and "about" actions
                ['actions' => ['index', 'about'], 'allow' => '*'],
                // Allow authorized users to visit "settings" action
                ['actions' => ['settings'], 'allow' => '@']
            ],
            // Apigility Admin controllers
            Admin\Controller\App::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\AppController::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\ApigilityVersionController::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\Authentication::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\AuthenticationController::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\AuthenticationType::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\Authorization::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\AuthorizationController::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\CacheEnabledController::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\Config::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\ConfigController::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\Dashboard::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\DbAutodiscovery::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\DoctrineAdapter::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\Documentation::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\Filters::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\FsPermissionsController::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\Hydrators::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\InputFilter::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\ModuleConfig::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\ModuleConfigController::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\ModuleCreation::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\ModuleCreationController::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\OAuth2Authentication::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\Package::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\Source::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\Validators::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            Admin\Controller\Versioning::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],

            // Apigility+Doctrine ORM Admin controllers
            DoctrineAdmin\Controller\DoctrineAutodiscovery::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            DoctrineAdmin\Controller\DoctrineRestService::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            DoctrineAdmin\Controller\DoctrineRpcService::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
            DoctrineAdmin\Controller\DoctrineMetadataService::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+api.manage']
            ],
        ]
    ],
    // This key stores configuration for RBAC manager.
    'rbac_manager' => [
        'assertions' => [Service\RbacAssertionManager::class],
    ],
    'service_manager' => [
        'factories' => [
            Service\NavManager::class => Service\Factory\NavManagerFactory::class,
            Service\RbacAssertionManager::class => Service\Factory\RbacAssertionManagerFactory::class,
            'Zend\Log' => function ($sm) {
                $log = new Logger();

                $streamWriter = new LogWriter\Stream('./data/logs/application.log');
                $log->addWriter($streamWriter);

                $chromePhpWriter = new LogWriter\ChromePhp();
                $log->addWriter($chromePhpWriter);

                return $log;
            },
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => View\Helper\Factory\MenuFactory::class,
            View\Helper\Breadcrumbs::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class,
            'pageBreadcrumbs' => View\Helper\Breadcrumbs::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    // The following key allows to define custom styling for FlashMessenger view helper.
    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format'      => '<div%s><ul><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        ]
    ],   
];
