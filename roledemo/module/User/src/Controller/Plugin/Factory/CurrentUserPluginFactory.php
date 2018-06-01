<?php
namespace User\Controller\Plugin\Factory;

use Interop\Container\ContainerInterface;
use User\Controller\Plugin\CurrentUserPlugin;
use Zend\Authentication\AuthenticationService;

/**
 * This is the factory for CurrentUserPlugin.
 * Its purpose is to instantiate the plugin and inject dependencies into its constructor.
 */
class CurrentUserPluginFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $entityManager  = $container->get('doctrine.entitymanager.orm_default');
        $authService    = $container->get(AuthenticationService::class);

        return new CurrentUserPlugin($entityManager, $authService);
    }
}


