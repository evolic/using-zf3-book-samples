<?php
namespace User\Service;

use User\Event\ImpersonateEvents;
use Zend\Authentication\AuthenticationService;
use User\Entity\User;
use Zend\Authentication\Result;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Log\Logger;
use Zend\Session\SessionManager;
use User\Service\RbacManager;

class ImpersonateManager
{
    /**
     * Authentication service.
     *
     * @var AuthenticationService
     */
    private $authService;

    /**
     * Impersonation service.
     *
     * @var ImpersonateService
     */
    private $impersonateService;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;


    /**
     * Constructs the service.
     *
     * @param  AuthenticationService  $authService
     * @param  ImpersonateService     $impersonateService
     */
    public function __construct(
        AuthenticationService $authService,
        ImpersonateService $impersonateService
    )
    {
        $this->authService          = $authService;
        $this->impersonateService   = $impersonateService;
    }


    /**
     * Impersonates given user and stores info about old one
     *
     * @param  User  $currentUser
     * @param  User  $userToBeImpersonate
     * @return boolean
     */
    public function impersonate(User $currentUser, User $userToBeImpersonate)
    {
        $adminEmail = $currentUser->getEmail();
        $userEmail  = $userToBeImpersonate->getEmail();

        $this->impersonateService->getStorage()->write($adminEmail);
        $this->authService->getStorage()->write($userEmail);

        $this->getEventManager()->trigger(ImpersonateEvents::EVENT_NAME_IMPERSONATE, $this, [
            [
                'admin' => $adminEmail,
                'user'  => $userEmail,
            ],
            sprintf(ImpersonateEvents::EVENT_MESSAGE_IMPERSONATE, $userEmail, $adminEmail),
            Logger::NOTICE
        ]);

        return true;
    }

    /**
     * Restores original user (switched by impersonation).
     *
     * @return boolean
     */
    public function unimpersonate()
    {
        $adminEmail = $this->impersonateService->getStorage()->read();
        $userEmail  = $this->authService->getStorage()->read();

        $this->impersonateService->getStorage()->clear();
        $this->authService->getStorage()->write($adminEmail);

        $this->getEventManager()->trigger(ImpersonateEvents::EVENT_NAME_UNIMPERSONATE, $this, [
            [
                'admin' => $adminEmail,
                'user'  => $userEmail,
            ],
            sprintf(ImpersonateEvents::EVENT_MESSAGE_UNIMPERSONATE, $userEmail, $adminEmail),
            Logger::NOTICE
        ]);

        return true;
    }

    /**
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers([
            __CLASS__,
            get_class($this)
        ]);

        $this->eventManager = $eventManager;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager(): EventManagerInterface
    {
        if (! $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }
}