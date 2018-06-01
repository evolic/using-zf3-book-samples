<?php
namespace User\Service;

use Zend\Authentication\Storage\StorageInterface;
use Zend\Authentication\Storage\Session as SessionStorage;

/**
 * Class ImpersonateService
 *
 * @package User\Service
 */
class ImpersonateService
{
    const DEFAULT_SESSION_NAMESPACE = 'Impersonate';
    const DEFAULT_SESSION_MEMBER    = 'impersonate';

    /**
     * Persistent storage handler
     *
     * @var StorageInterface
     */
    protected $storage = null;


    /**
     * Constructor
     *
     * @param  StorageInterface  $storage
     */
    public function __construct(StorageInterface $storage = null)
    {
        if (null !== $storage) {
            $this->setStorage($storage);
        }
    }


    /**
     * Returns the persistent storage handler
     *
     * Session storage is used by default unless a different storage adapter has been set.
     *
     * @return StorageInterface
     */
    public function getStorage()
    {
        if (null === $this->storage) {
            $this->setStorage(new SessionStorage(
                self::DEFAULT_SESSION_NAMESPACE,
                self::DEFAULT_SESSION_MEMBER
            ));
        }

        return $this->storage;
    }

    /**
     * Sets the persistent storage handler
     *
     * @param  StorageInterface $storage
     * @return self Provides a fluent interface
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return ! $this->getStorage()->isEmpty();
    }

    /**
     * Returns the identity from storage or null if no identity is available
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        $storage = $this->getStorage();

        if ($storage->isEmpty()) {
            return;
        }

        return $storage->read();
    }

    /**
     * Clears the identity from persistent storage
     *
     * @return void
     */
    public function clearIdentity()
    {
        $this->getStorage()->clear();
    }
}
