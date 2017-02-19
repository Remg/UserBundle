<?php

namespace Remg\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Remg\UserBundle\Entity\Admin;

class LoadAdminData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
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
        $admin = new Admin();

        $admin
            ->setEmail('admin@example.com')
            ->setPlainPassword('admin123')
            ->addGroup(
                $manager
                    ->getRepository('RemgUserBundle:Group')
                    ->findOneByName('Super Administrators')
            )
            ->setPassword(
                $this
                    ->container
                    ->get('security.password_encoder')
                    ->encodePassword($admin, $admin->getPlainPassword())
            );

        $manager->persist($admin);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}