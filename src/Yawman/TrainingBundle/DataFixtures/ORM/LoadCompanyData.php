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
        $iptCompany = new Company();
        $iptCompany->setName('International Produce Training');
        $manager->persist($iptCompany);
        
        $aldiCompany = new Company();
        $aldiCompany->setName('Aldi');
        $manager->persist($aldiCompany);
        
        $manager->flush();
        
        $this->addReference('ipt-company', $iptCompany);
        $this->addReference('aldi-company', $aldiCompany);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 2;
    }
}