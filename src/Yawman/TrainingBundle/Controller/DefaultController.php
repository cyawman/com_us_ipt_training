<?php

namespace Yawman\TrainingBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\Form\FormError;
use Yawman\TrainingBundle\Model\Registration;
use Yawman\TrainingBundle\Form\RegistrationType;
use Yawman\TrainingBundle\Entity\User;

/**
 * Controls the management of Default actions
 * 
 * @author Chris Yawman
 */
class DefaultController extends Controller {

    /**
     * Displays the Homepage
     * 
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        return $this->render('YawmanTrainingBundle:Default:index.html.twig');
    }

    public function registrationAction() {
        $form = $this->createForm(new RegistrationType(), new Registration());

        return $this->render('YawmanTrainingBundle:Default:registration.html.twig', array('form' => $form->createView()));
    }

    public function registerAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $form = $this->createForm(new RegistrationType(), new Registration());

        $form->bind($this->getRequest());

        try {
            if ($form->isValid()) {
                $registration = $form->getData();

                if ($registration->getPassword() != $registration->getPasswordConfirm()) {
                    $form->addError(new FormError('Password and Confirmation Password did not match'));
                    throw new \Exception("Manual form error added");
                }
                
                $entity = $em->getRepository('YawmanTrainingBundle:User')->findOneBy(array('email' => $registration->getEmail()));
                
                if($entity){
                    $form->addError(new FormError('This email address already exists in the system.'));
                    throw new \Exception("Manual form error added");
                }
                
                $company = $em->getRepository('YawmanTrainingBundle:Company')->findOneBy(array('name' => 'individual'));
                $group = $em->getRepository('YawmanTrainingBundle:Group')->findOneBy(array('name' => 'user'));
                
                $user = new User();
                $user->setUsername($registration->getEmail());
                $user->setEmail($registration->getEmail());
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($registration->getPassword(), $user->getSalt());
                $user->setPassword($password);
                $user->setCompany($company);
                $user->addGroup($group);
                
                $em->persist($user);
                $em->flush();
                
                return $this->redirect($this->generateUrl('login'));
            }
        } catch (\Exception $e) {
            
        }

        return $this->render('YawmanTrainingBundle:Default:registration.html.twig', array('form' => $form->createView()));
    }

}
