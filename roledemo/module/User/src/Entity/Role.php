<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a role.
 * @ORM\Entity()
 * @ORM\Table(name="role")
 */
class Role
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="name")  
     */
    protected $name;
    
    /** 
     * @ORM\Column(name="description")  
     */
    protected $description;

    /** 
     * @ORM\Column(name="date_created")
     */
    protected $dateCreated;

    /**
     * @ORM\Column(name="can_impersonate", type="boolean", nullable=false)
     */
    protected $canImpersonate;

    /**
     * @ORM\Column(name="can_be_impersonated", type="boolean", nullable=false)
     */
    protected $canBeImpersonated;


    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="role_hierarchy",
     *      joinColumns={@ORM\JoinColumn(name="child_role_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parent_role_id", referencedColumnName="id")}
     *      )
     */
    private $parentRoles;
    
    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="role_hierarchy",
     *      joinColumns={@ORM\JoinColumn(name="parent_role_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="child_role_id", referencedColumnName="id")}
     *      )
     */
    protected $childRoles;
    
    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Permission")
     * @ORM\JoinTable(name="role_permission",
     *      joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="permission_id", referencedColumnName="id")}
     *      )
     */
    private $permissions;
    
    /**
     * Constructor.
     */
    public function __construct() 
    {
        $this->parentRoles = new ArrayCollection();
        $this->childRoles = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }
    
    /**
     * Returns role ID.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Sets role ID. 
     * @param int $id    
     */
    public function setId($id) 
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    public function canImpersonate()
    {
        return $this->canImpersonate;
    }

    public function getCanImpersonate()
    {
        return $this->canImpersonate;
    }

    public function setCanImpersonate($canImpersonate)
    {
        $this->canImpersonate = $canImpersonate;
    }

    public function canBeImpersonated()
    {
        return $this->canBeImpersonated;
    }

    public function getCanBeImpersonated()
    {
        return $this->canBeImpersonated;
    }

    public function setCanBeImpersonated($canBeImpersonated)
    {
        $this->canBeImpersonated = $canBeImpersonated;
    }

    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }
    
    public function getParentRoles()
    {
        return $this->parentRoles;
    }
    
    public function getChildRoles()
    {
        return $this->childRoles;
    }
    
    public function getPermissions()
    {
        return $this->permissions;
    }

    public function addParent(Role $role)
    {
        if ($this->getId() == $role->getId()) {
            return false;
        }
        if (!$this->hasParent($role)) {
            $this->parentRoles[] = $role;
            return true;
        }
        return false;
    }

    /**
     * Clear parent roles
     */
    public function clearParentRoles()
    {
        $this->parentRoles = new ArrayCollection();
    }

    /**
     * Check if parent role exists
     * @param Role $role
     * @return bool
     */
    public function hasParent(Role $role)
    {
        if ($this->getParentRoles()->contains($role)) {
            return true;
        }
        return false;
    }
}



