<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\NavManager;
use User\Service\ImpersonateService;
use User\Service\RbacManager;
use Zend\Authentication\AuthenticationService;

/**
 * This is the factory class for NavManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class NavManagerFactory
{
    /**
     * This method creates the NavManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authService        = $container->get(AuthenticationService::class);
        $impersonateService = $container->get(ImpersonateService::class);

        $viewHelperManager  = $container->get('ViewHelperManager');
        $urlHelper          = $viewHelperManager->get('url');
        $rbacManager        = $container->get(RbacManager::class);

        return new NavManager($authService, $impersonateService, $urlHelper, $rbacManager);
    }
}
