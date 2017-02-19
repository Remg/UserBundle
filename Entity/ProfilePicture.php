<?php

namespace Remg\UserBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Remg\MediaBundle\Model\Image;

/**
 * @ORM\Entity
 * @ORM\Table(name="`profile_picture`")
 * @ORM\HasLifecycleCallbacks
 */
class ProfilePicture extends Image
{
    const UPLOAD_DIRECTORY = 'uploads/profile-pictures';
    const DEFAULT_IMAGE    = 'bundles/remguser/images/user.png';
}