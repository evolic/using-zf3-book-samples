<?php
namespace User\Service;

use Doctrine\ORM\EntityManager;
use User\Event\AuthEvents;
use Zend\Authentication\Result;
use Zend\Session\SessionManager;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Log\Logger;
use User\Service\RbacManager;
use User\Util\UserAgent;

/**
 * The AuthManager service is responsible for user's login/logout and simple access 
 * filtering. The access filtering feature checks whether the current visitor 
 * is allowed to see the given page or not.  
 */
class AuthManager implements EventManagerAwareInterface
{
    // Constants returned by the access filter.
    const ACCESS_GRANTED = 1; // Access to the page is granted.
    const AUTH_REQUIRED  = 2; // Authentication is required to see the page.
    const ACCESS_DENIED  = 3; // Access to the page is denied.
    
    /**
     * Authentication service.
     *
     * @var AuthenticationService
     */
    private $authService;
    
    /**
     * Session manager.
     *
     * @var SessionManager
     */
    private $sessionManager;
    
    /**
     * Contents of the 'access_filter' config key.
     *
     * @var array 
     */
    private $config;
    
    /**
     * RBAC manager.
     *
     * @var RbacManager
     */
    private $rbacManager;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    
    /**
     * Constructs the service.
     *
     * @param  AuthenticationService  $authService
     * @param  SessionManager         $sessionManager
     * @param  array                  $config
     * @param  RbacManager            $rbacManager
     */
    public function __construct(
        AuthenticationService $authService,
        SessionManager $sessionManager,
        array $config,
        RbacManager $rbacManager
    )
    {
        $this->authService      = $authService;
        $this->sessionManager   = $sessionManager;
        $this->config           = $config;
        $this->rbacManager      = $rbacManager;
    }
    
    /**
     * Performs a login attempt. If $rememberMe argument is true, it forces the session
     * to last for one month (otherwise the session expires on one hour).
     */
    public function login($email, $password, $rememberMe)
    {
        $this->getEventManager()->trigger(AuthEvents::EVENT_NAME_LOGIN_ATTEMPT, $this, [
            [],
            sprintf(AuthEvents::EVENT_MESSAGE_LOGIN_ATTEMPT, $email),
            Logger::NOTICE
        ]);

        // Check if user has already logged in. If so, do not allow to log in 
        // twice.
        if ($this->authService->getIdentity() != null) {
            $this->getEventManager()->trigger(AuthEvents::EVENT_NAME_LOGIN_FAILED, $this, [
                [
                    'email' => $email,
                ],
                'Already logged in',
                Logger::NOTICE
            ]);

            throw new \Exception('Already logged in', 400);
        }
            
        // Authenticate with login/password.
        $authAdapter = $this->authService->getAdapter();

        $authAdapter->setEmail($email);
        $authAdapter->setPassword($password);

        $result = $this->authService->authenticate();

        if ($result->getCode() == Result::SUCCESS) {
            $this->sessionManager->regenerateId(true);

            $this->getEventManager()->trigger(AuthEvents::EVENT_NAME_LOGIN_SUCCESSFUL, $this, [
                [],
                sprintf(AuthEvents::EVENT_MESSAGE_LOGIN_SUCCESSFUL, $email),
                Logger::INFO
            ]);
        } else {
            $this->getEventManager()->trigger(AuthEvents::EVENT_NAME_LOGIN_FAILED, $this, [
                [
                    'ipAddress' => UserAgent::getClientIp(),
                    'userAgent' => $_SERVER['HTTP_USER_AGENT'],
                ],
                sprintf(AuthEvents::EVENT_MESSAGE_LOGIN_FAILED, $email),
                Logger::NOTICE
            ]);
        }

        // If user wants to "remember him", we will make session to expire in 
        // one month. By default session expires in 1 hour (as specified in our 
        // config/global.php file).
        if ($result->getCode() == Result::SUCCESS && $rememberMe) {
            // Session cookie will expire in 1 month (30 days).
            $this->sessionManager->rememberMe(60*60*24*30);
        }
        
        return $result;
    }
    
    /**
     * Performs user logout.
     */
    public function logout()
    {


        // Allow to log out only when user is logged in.
        if ($this->authService->getIdentity() == null) {
            throw new \Exception('The user is not logged in');
        }

        // Remove identity from session.
        $this->authService->clearIdentity();

        $this->sessionManager->regenerateId(true);
    }
    
    /**
     * This is a simple access control filter. It is able to restrict unauthorized
     * users to visit certain pages.
     * 
     * This method uses the 'access_filter' key in the config file and determines
     * whenther the current visitor is allowed to access the given controller action
     * or not. It returns true if allowed; otherwise false.
     */
    public function filterAccess($controllerName, $actionName)
    {
        // Determine mode - 'restrictive' (default) or 'permissive'. In restrictive
        // mode all controller actions must be explicitly listed under the 'access_filter'
        // config key, and access is denied to any not listed action for unauthorized users. 
        // In permissive mode, if an action is not listed under the 'access_filter' key, 
        // access to it is permitted to anyone (even for not logged in users.
        // Restrictive mode is more secure and recommended to use.
        $mode = isset($this->config['options']['mode'])?$this->config['options']['mode']:'restrictive';
        if ($mode!='restrictive' && $mode!='permissive')
            throw new \Exception('Invalid access filter mode (expected either restrictive or permissive mode');
        
        if (isset($this->config['controllers'][$controllerName])) {
            $items = $this->config['controllers'][$controllerName];
            foreach ($items as $item) {
                $actionList = $item['actions'];
                $allow = $item['allow'];
                if (is_array($actionList) && in_array($actionName, $actionList) ||
                    $actionList=='*') {
                    if ($allow=='*')
                        // Anyone is allowed to see the page.
                        return self::ACCESS_GRANTED; 
                    else if (!$this->authService->hasIdentity()) {
                        // Only authenticated user is allowed to see the page.
                        return self::AUTH_REQUIRED;                        
                    }
                        
                    if ($allow=='@') {
                        // Any authenticated user is allowed to see the page.
                        return self::ACCESS_GRANTED;                         
                    } else if (substr($allow, 0, 1)=='@') {
                        // Only the user with specific identity is allowed to see the page.
                        $identity = substr($allow, 1);
                        if ($this->authService->getIdentity()==$identity)
                            return self::ACCESS_GRANTED; 
                        else
                            return self::ACCESS_DENIED;
                    } else if (substr($allow, 0, 1)=='+') {
                        // Only the user with this permission is allowed to see the page.
                        $permission = substr($allow, 1);
                        if ($this->rbacManager->isGranted(null, $permission))
                            return self::ACCESS_GRANTED; 
                        else
                            return self::ACCESS_DENIED;
                    } else {
                        throw new \Exception('Unexpected value for "allow" - expected ' .
                                'either "?", "@", "@identity" or "+permission"');
                    }
                }
            }            
        }
        
        // In restrictive mode, we require authentication for any action not 
        // listed under 'access_filter' key and deny access to authorized users 
        // (for security reasons).
        if ($mode=='restrictive') {
            if(!$this->authService->hasIdentity())
                return self::AUTH_REQUIRED;
            else
                return self::ACCESS_DENIED;
        }
        
        // Permit access to this page.
        return self::ACCESS_GRANTED;
    }

    /**
     * Method used to inject EntityManager.
     *
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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