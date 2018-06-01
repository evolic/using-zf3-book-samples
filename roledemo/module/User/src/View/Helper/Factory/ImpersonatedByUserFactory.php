<?php
namespace User\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use User\Service\ImpersonateService;
use User\View\Helper\CurrentUser;

class ImpersonatedByUserFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $entityManager      = $container->get('doctrine.entitymanager.orm_default');
        $impersonateService = $container->get(ImpersonateService::class);

        return new ImpersonatedByUser($entityManager, $impersonateService);
    }
}
