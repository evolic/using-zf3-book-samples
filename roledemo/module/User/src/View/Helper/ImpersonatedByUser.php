<?php
namespace User\View\Helper;

use Doctrine\ORM\EntityManager;
use Zend\View\Helper\AbstractHelper;
use User\Entity\User;
use User\Service\ImpersonateService;

/**
 * This view helper is used for retrieving the User entity of the user switched by impersonation.
 */
class ImpersonatedByUser extends AbstractHelper
{
    /**
     * Entity manager.
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Impersonation service.
     *
     * @var ImpersonateService
     */
    private $impersonateService;

    /**
     * Previously fetched User entity.
     * @var \User\Entity\User
     */
    private $user = null;
    
    /**
     * Constructor.
     *
     * @param  EntityManager       $entityManager
     * @param  ImpersonateService  $impersonateService
     */
    public function __construct(EntityManager $entityManager, ImpersonateService $impersonateService)
    {
        $this->entityManager        = $entityManager;
        $this->impersonateService   = $impersonateService;
    }
    
    /**
     * Returns the User, who impersonated another user or null if non user had been impersonated.
     *
     * @param  bool  $useCachedUser If true, the User entity is fetched only on the first call (and cached on subsequent calls).
     * @return User|null
     */
    public function __invoke($useCachedUser = true)
    {
        // Check if User is already fetched previously.
        if ($useCachedUser && $this->user !== null) {
            return $this->user;
        }

        // Check if user is logged in.
        if ($this->impersonateService->hasIdentity()) {
            // Fetch User entity from database.
            $this->user = $this->entityManager->getRepository(User::class)->findOneBy(array(
                'email' => $this->impersonateService->getIdentity()
            ));

            if ($this->user == null) {
                // Oops.. the identity presents in session, but there is no such user in database.
                // We throw an exception, because this is a possible security problem. 
                throw new \Exception('Not found user with such ID');
            }

            // Return the User entity we found.
            return $this->user;
        }

        return null;
    }
}
