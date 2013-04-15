<?php


namespace Yawman\TrainingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Yawman\TrainingBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {
    
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.encoder_factory');
        
        $cyawmanUser = new User();
        $cyawmanUser->setUsername('cyawman');
        $cyawmanUser->setEmail('cyawman@gmail.com');
        $cyawmanUser->setIsActive(true);
        $cyawmanUser->setCompany($this->getReference('ipt-company'));
        $cyawmanEncoder = $encoder->getEncoder($cyawmanUser);
        $cyawmanUser->setPassword($cyawmanEncoder->encodePassword('cnasty328', $cyawmanUser->getSalt()));
        $cyawmanUser->addGroup($manager->merge($this->getReference('admin-group')));

        $manager->persist($cyawmanUser);
        
        $tyawmanUser = new User();
        $tyawmanUser->setUsername('tyawman');
        $tyawmanUser->setEmail('tyawman@ipt.us.com');
        $tyawmanUser->setIsActive(true);
        $tyawmanUser->setCompany($this->getReference('ipt-company'));
        $tyawmanEncoder = $encoder->getEncoder($tyawmanUser);
        $tyawmanUser->setPassword($tyawmanEncoder->encodePassword('tanterra33', $tyawmanUser->getSalt()));
        $tyawmanUser->addGroup($manager->merge($this->getReference('admin-group')));
        
        $manager->persist($tyawmanUser);
        
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 3;
    }
}