<?php

namespace Yawman\TrainingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use \Symfony\Component\Form\FormError;
use Yawman\TrainingBundle\Model\ChangePassword;
use Yawman\TrainingBundle\Form\ChangePasswordType;

class SecurityController extends Controller {

    public function loginAction() {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
                        'YawmanTrainingBundle:Security:login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                        )
        );
    }

    public function changePasswordAction() {
        $form = $this->createForm(new ChangePasswordType, new ChangePassword);

        return $this->render('YawmanTrainingBundle:Security:change-password.html.twig', array('form' => $form->createView()));
    }

    public function updatePasswordAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $form = $this->createForm(new ChangePasswordType, new ChangePassword);

        $form->bind($this->getRequest());

        try {
            if ($form->isValid()) {
                $data = $form->getData();

                $user = $this->getUser();

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $currentPassword = $encoder->encodePassword($data->getCurrentPassword(), $user->getSalt());

                if ($currentPassword != $user->getPassword()) {
                    $form->addError(new FormError('Your Current Password was invalid. Please try again.'));
                    throw new \Exception("Manual form error added");
                }

                if ($data->getPassword() != $data->getPasswordConfirm()) {
                    $form->addError(new FormError('Your New Password and Confirmation Password did not match'));
                    throw new \Exception("Manual form error added");
                }

                $newPassword = $encoder->encodePassword($data->getPassword(), $user->getSalt());
                $user->setPassword($newPassword);

                $em->persist($user);
                $em->flush();

                $this->get('session')->setFlash('success', 'Your password was updated!');

                return $this->redirect($this->generateUrl('yawman_training_change_password'));
            }
        } catch (\Exception $e) {
            
        }

        return $this->render('YawmanTrainingBundle:Security:change-password.html.twig', array('form' => $form->createView()));
    }

}
