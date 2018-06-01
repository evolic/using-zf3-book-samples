<?php
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use User\Controller\ImpersonateController;
use User\Event\Listener\LoggerListener;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\Service\ImpersonateManager;

/**
 * This is the factory for ImpersonateController.
 * Its purpose is to instantiate the controller and inject dependencies into its constructor.
 */
class ImpersonateControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager      = $container->get('doctrine.entitymanager.orm_default');
        $impersonateManager = $container->get(ImpersonateManager::class);
        $loggerListener = $container->get(LoggerListener::class);

        return new ImpersonateController($entityManager, $impersonateManager, $loggerListener);
    }
}
