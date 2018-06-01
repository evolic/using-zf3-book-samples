<?php
namespace User\Controller;

use Doctrine\ORM\EntityManager;
use User\Event\Listener\LoggerListener;
use Zend\Mvc\Controller\AbstractActionController;
use User\Entity\User;
use User\Service\ImpersonateManager;

/**
 * Class ImpersonateController
 *
 * This controller is responsible for letting the admin user to impersonate another user
 * and leave this mode.
 *
 * @package User\Controller
 */
class ImpersonateController extends AbstractActionController
{
    /**
     * Entity manager.
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Impersonate manager.
     *
     * @var ImpersonateManager
     */
    private $impersonateManager;


    /**
     * Constructor.
     *
     * @param  EntityManager       $entityManager
     * @param  ImpersonateManager  $impersonateManager
     * @param  LoggerListener      $loggerListener
     */
    public function __construct(
        EntityManager $entityManager,
        ImpersonateManager $impersonateManager,
        LoggerListener $loggerListener
    )
    {
        $this->entityManager        = $entityManager;
        $this->impersonateManager   = $impersonateManager;

        $loggerListener->attachEvents($impersonateManager->getEventManager());
    }


    /**
     * Impersonate another user.
     */
    public function impersonateAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->currentUser();

        if ($currentUser === null) {
            // Add a flash message.
            $this->flashMessenger()->addErrorMessage('Not authenticated.');

            return $this->redirect()->toRoute('users', []);
        }

        if (! $currentUser->canImpersonate()) {
            // Add a flash message.
            $this->flashMessenger()->addErrorMessage('You are not allowed to impersonate other users.');

            return $this->redirect()->toRoute('users', []);
        }

        $id = (int) $this->params()->fromRoute('id', -1);

        if ($id < 1) {
            // Add a flash message.
            $this->flashMessenger()->addErrorMessage('User not found.');

            return $this->redirect()->toRoute('users', []);
        }

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if ($user === null) {
            // Add a flash message.
            $this->flashMessenger()->addErrorMessage('User not found.');

            return $this->redirect()->toRoute('users', []);
        }

        if (! $user->canBeImpersonated()) {
            // Add a flash message.
            $this->flashMessenger()->addErrorMessage(sprintf('User \'%s\' cannot be impersonated.', $user->getFullName()));

            return $this->redirect()->toRoute('users', []);
        }

        if ($this->impersonateManager->impersonate($currentUser, $user)) {
            $this->flashMessenger()->addSuccessMessage(sprintf('Impersonated as %s.', $user->getFullName()));
        } else {
            $this->flashMessenger()->addErrorMessage(sprintf('Impersonation as %s failed.', $user->getFullName()));
        }

        return $this->redirect()->toRoute('users', []);
    }

    /**
     * Unimpersonate another user.
     */
    public function unimpersonateAction()
    {
        if ($this->impersonateManager->unimpersonate()) {
            $this->flashMessenger()->addSuccessMessage('Impersonation stopped.');
        } else {
            $this->flashMessenger()->addErrorMessage(sprintf('Stopping impersonation failed.'));
        }

        return $this->redirect()->toRoute('users', []);
    }
}