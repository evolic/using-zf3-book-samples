<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\ImpersonateService;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use User\Service\AuthAdapter;

/**
 * The factory responsible for creating of impersonation service.
 */
class ImpersonateServiceFactory implements FactoryInterface
{
    /**
     * This method creates the ImpersonateService service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager     = $container->get(SessionManager::class);
        $impersonateStorage = new SessionStorage(
            ImpersonateService::DEFAULT_SESSION_NAMESPACE,
            ImpersonateService::DEFAULT_SESSION_MEMBER,
            $sessionManager
        );

        // Create the service and inject dependencies into its constructor.
        return new ImpersonateService($impersonateStorage);
    }
}

