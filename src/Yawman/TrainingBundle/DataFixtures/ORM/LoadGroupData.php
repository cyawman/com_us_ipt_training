<?php

namespace Yawman\TrainingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Yawman\TrainingBundle\Entity\Group;

class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $userGroup = new Group();
        $userGroup->setName('user');
        $userGroup->setRole('ROLE_USER');
        $manager->persist($userGroup);

        $managerGroup = new Group();
        $managerGroup->setName('manager');
        $managerGroup->setRole('ROLE_MANAGER');
        $manager->persist($managerGroup);

        $adminGroup = new Group();
        $adminGroup->setName('admin');
        $adminGroup->setRole('ROLE_ADMIN');
        $manager->persist($adminGroup);

        $manager->flush();
        
        $this->addReference('user-group', $userGroup);
        $this->addReference('manager-group', $managerGroup);
        $this->addReference('admin-group', $adminGroup);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 1;
    }

}