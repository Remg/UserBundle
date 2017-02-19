<?php

namespace Remg\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Remg\UserBundle\Model\Group as BaseGroup;

/**
 * @ORM\Entity
 * @ORM\Table(name="`group`")
 */
class Group extends BaseGroup
{
}