<?php

namespace Remg\UserBundle\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Remg\UserBundle\Model\User;
use Remg\UserBundle\Util\PasswordUpdater;

class UserSubscriber implements EventSubscriber
{
    private $passwordUpdater;

    public function __construct(PasswordUpdater $passwordUpdater)
    {
        $this->passwordUpdater = $passwordUpdater;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updatePassword($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->updatePassword($args);
    }

    public function updatePassword(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $this->passwordUpdater->updatePassword($entity);
        }
    }
}