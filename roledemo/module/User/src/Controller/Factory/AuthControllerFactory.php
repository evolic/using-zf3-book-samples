<?php
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\Controller\AuthController;
use User\Event\Listener\LoggerListener;
use User\Service\AuthManager;
use User\Service\UserManager;

/**
 * This is the factory for AuthController.
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 */
class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager  = $container->get('doctrine.entitymanager.orm_default');
        $authManager    = $container->get(AuthManager::class);
        $userManager    = $container->get(UserManager::class);
        $loggerListener = $container->get(LoggerListener::class);

        return new AuthController($entityManager, $authManager, $userManager, $loggerListener);
    }
}
