<?php

namespace Yawman\TrainingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\Form\FormError;
use Yawman\TrainingBundle\Entity\User;
use Yawman\TrainingBundle\Form\AdminUserType;
use Yawman\TrainingBundle\Model\ChangePassword;
use Yawman\TrainingBundle\Form\ChangePasswordType;

class UserController extends Controller {

    /**
     * Lists all User entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('YawmanTrainingBundle:User')->findAll();

        return $this->render('YawmanTrainingBundle:User:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:User:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction() {
        $entity = new User();
        $form = $this->createForm(new AdminUserType(), $entity);

        return $this->render('YawmanTrainingBundle:User:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new User();
        $form = $this->createForm(new AdminUserType(), $entity);
        $form->bind($request);

        try {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $data = $form->getData();

                $entity->setUsername($data->getEmail());

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($entity);
                $password = $encoder->encodePassword($data->getPassword(), $entity->getSalt());

                $entity->setPassword($password);

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
            }
        } catch (\Exception $e) {
            
        }

        $this->get('session')->setFlash('error', 'There was an error creating the account.');

        return $this->render('YawmanTrainingBundle:User:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new AdminUserType(), $entity);
        $editForm->remove('password');
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:User:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AdminUserType(), $entity);
        $editForm->remove('password');
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:User:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit the password for an existing User entity.
     *
     */
    public function editPasswordAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $form = $this->createForm(new ChangePasswordType, new ChangePassword);
        $form->remove('current_password');

        return $this->render('YawmanTrainingBundle:User:edit-password.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Edits the password for an existing User entity.
     *
     */
    public function updatePasswordAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $form = $this->createForm(new ChangePasswordType, new ChangePassword);
        $form->remove('current_password');
        $form->bind($request);

        try {
            if ($form->isValid()) {

                $data = $form->getData();

                if ($data->getPassword() != $data->getPasswordConfirm()) {
                    $form->addError(new FormError('Your New Password and Confirmation Password did not match'));
                    throw new \Exception("Manual form error added");
                }

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($entity);

                $newPassword = $encoder->encodePassword($data->getPassword(), $entity->getSalt());

                $entity->setPassword($newPassword);

                $em->persist($entity);
                $em->flush();

                $this->get('session')->setFlash('success', 'Password was updated!');

                return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
            }
        } catch (\Exception $e) {
            
        }


        return $this->render('YawmanTrainingBundle:User:edit-password.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
