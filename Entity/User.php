<?php

namespace Remg\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Remg\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass="Remg\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
}