<?php

namespace Remg\UserBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;

    /**
     * @Assert\NotBlank(
     *     message = "group.name.constraint.notBlank"
     * )
     * @Assert\Length(
     *     min = "3",
     *     minMessage = "group.name.constraint.length"
     * )
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @Assert\Count(
     *      min = "1",
     *      minMessage = "group.roles.constraint.count.min"
     * )
     *
     * @ORM\Column(type="array")
     */
    protected $roles;

    public function __construct()
    {
        $this->roles = [];
    }

    /**
     * toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->roles, true);
    }

    public function addRole($role)
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = strtoupper($role);
        }

        return $this;
    }

    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }
}