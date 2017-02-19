<?php

namespace Remg\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Remg\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user
            ->setEmail('user@example.com')
            ->setPlainPassword('user123')
            ->setPassword(
                $this
                    ->container
                    ->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword())
            );

        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}