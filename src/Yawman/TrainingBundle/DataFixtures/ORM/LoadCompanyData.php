<?php


namespace Yawman\TrainingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Yawman\TrainingBundle\Entity\Company;

class LoadCompanyData extends AbstractFixture implements OrderedFixtureInterface {
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $individualCompany = new Company();
        $individualCompany->setName('individual');
        $manager->persist($individualCompany);
        
        $iptCompany = new Company();
        $iptCompany->setName('international produce training');
        $manager->persist($iptCompany);
        
        $manager->flush();
        
        $this->addReference('individual-company', $individualCompany);
        $this->addReference('ipt-company', $iptCompany);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 2;
    }
}