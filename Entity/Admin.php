<?php

namespace Remg\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Remg\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Table(name="`admin`")
 * @ORM\Entity(repositoryClass="Remg\UserBundle\Repository\AdminRepository")
 * @ORM\AssociationOverrides({
 *      @ORM\AssociationOverride(
 *          name="groups",
 *          joinTable=@ORM\JoinTable(
 *              name="admins_groups",
 *              joinColumns={@ORM\JoinColumn(name="admin_id")},
 *              inverseJoinColumns={@ORM\JoinColumn(name="group_id")}
 *          )
 *      )
 * })
 */
class Admin extends BaseUser
{
}