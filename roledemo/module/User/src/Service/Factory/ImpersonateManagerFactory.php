<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\ImpersonateService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\AuthenticationService;
use User\Service\ImpersonateManager;

/**
 * This is the factory class for ImpersonateManager service.
 * The purpose of the factory is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ImpersonateManagerFactory implements FactoryInterface
{
    /**
     * This method creates the ImpersonateManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Instantiate dependencies.
        $authenticationService  = $container->get(AuthenticationService::class);
        $impersonateService     = $container->get(ImpersonateService::class);

        // Instantiate the AuthManager service and inject dependencies to its constructor.
        return new ImpersonateManager($authenticationService, $impersonateService);
    }
}
