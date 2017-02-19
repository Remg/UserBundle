<?php

namespace Remg\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Remg\UserBundle\Entity\Group;

class LoadGroupData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * Set container
     *
     * @param ContainerInterface
     *
     * @return self
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $group = new Group();

        $group
            ->setName('Administrators')
            ->addRole('ROLE_ADMIN');

        $manager->persist($group);


        $group = new Group();

        $group
            ->setName('Super Administrators')
            ->addRole('ROLE_SUPER_ADMIN');

        $manager->persist($group);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}