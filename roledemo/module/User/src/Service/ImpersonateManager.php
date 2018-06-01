<?php
namespace User\Service;

use Zend\Authentication\AuthenticationService;
use User\Entity\User;
use Zend\Authentication\Result;
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
     * Constructs the service.
     */
    public function __construct(AuthenticationService $authService, ImpersonateService $impersonateService)
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
        $this->impersonateService->getStorage()->write($currentUser->getEmail());

        $this->authService->getStorage()->write($userToBeImpersonate->getEmail());

        return true;
    }

    /**
     * Restores original user (switched by impersonation).
     *
     * @return boolean
     */
    public function unimpersonate()
    {
        $email = $this->impersonateService->getStorage()->read();

        $this->impersonateService->getStorage()->clear();
        $this->authService->getStorage()->write($email);

        return true;
    }
}