<?php

namespace Yawman\TrainingBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Yawman\TrainingBundle\Entity\User;
use Yawman\TrainingBundle\Exception\DuplicateEntryException;
use Yawman\TrainingBundle\Exception\PasswordConfirmationException;
use Yawman\TrainingBundle\Form\ChangePasswordType;
use Yawman\TrainingBundle\Form\UserType;
use Yawman\TrainingBundle\Model\ChangePassword;

/**
 * Controls the management of Users
 * 
 * @author Chris Yawman
 * @Route("/user")
 */
class UserController extends Controller {

    /**
     * Lists all User entities. Control which User entities are shown based on the current users role.
     * 
     * ROLE_USER can only list their own User entity
     * ROLE_MANAGER can only list User entities within the same Company as the current user.
     * ROLE_ADMIN can see all User entities for all Companies
     * 
     * @Route("/", name="user")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        if (false === $this->get('security.context')->isGranted('ROLE_MANAGER')) {
            $entities = $em->getRepository('YawmanTrainingBundle:User')->findBy(array('id' => $user->getId()));
        } elseif (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $entities = $em->getRepository('YawmanTrainingBundle:User')->findBy(array('company' => $user->getCompany()));
        } else {
            $entities = $em->getRepository('YawmanTrainingBundle:User')->findAll();
        }

        return $this->render('YawmanTrainingBundle:User:index.html.twig', array('entities' => $entities));
    }

    /**
     * Finds and displays a User entity
     * 
     * @Route("/{id}/show", requirements={"id" = "\d+"}, name="user_show")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $this->verifyUserActionPrivileges($this->getUser(), $entity);

        return $this->render('YawmanTrainingBundle:User:show.html.twig', array('entity' => $entity));
    }

    /**
     * Displays a form to create a new User entity.
     * 
     * @Route("/new", name="user_new")
     * @Secure(roles="ROLE_MANAGER")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     */
    public function newAction() {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $form->remove('company');
            $form->remove('groups');
        }

        return $this->render('YawmanTrainingBundle:User:new.html.twig', array('entity' => $entity, 'form' => $form->createView()));
    }

    /**
     * Creates a new User entity.
     * 
     * @Route("/create", requirements={"_method" = "post"}, name="user_create")
     * @Secure(roles="ROLE_MANAGER")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws DuplicateEntryException
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $entity = new User();
        $user = $this->getUser();

        $form = $this->createForm(new UserType(), $entity);
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $form->remove('company');
            $form->remove('groups');
        }
        $form->bind($request);

        try {
            if ($form->isValid()) {
                $data = $form->getData();

                if ($em->getRepository('YawmanTrainingBundle:User')->findOneBy(array('email' => $data->getEmail()))) {
                    $form->addError(new FormError('This email address is already in use.'));
                    throw new DuplicateEntryException('Email address must be unique');
                }

                if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
                    $group = $em->getRepository('YawmanTrainingBundle:Group')->findOneBy(array('role' => 'ROLE_USER'));
                    $entity->addGroup($group);
                    $entity->setCompany($user->getCompany());
                }

                $entity->setUsername($data->getEmail());

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($entity);
                $password = $encoder->encodePassword($data->getPassword(), $entity->getSalt());

                $entity->setPassword($password);

                $em->persist($entity);
                $em->flush();

                $this->get('session')->setFlash('success', 'The user was created successfully!');

                return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
            }
        } catch (DuplicateEntryException $e) {
            
        } catch (\Exception $e) {
            
        }

        $this->get('session')->setFlash('error', 'There was an error creating the account.');

        return $this->render('YawmanTrainingBundle:User:new.html.twig', array('entity' => $entity, 'form' => $form->createView()));
    }

    /**
     * Displays a form to edit an existing User entity.
     * 
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="user_edit")
     * @Secure(roles="ROLE_MANAGER")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $this->verifyUserActionPrivileges($this->getUser(), $entity);

        $editForm = $this->createForm(new UserType(), $entity);
        $editForm->remove('password');
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $editForm->remove('groups');
            $editForm->remove('company');
        }
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:User:edit.html.twig', array('entity' => $entity, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()));
    }

    /**
     * Displays a form to edit an existing User entity.
     * 
     * @Route("/{id}/update", requirements={"_method" = "post", "id" = "\d+"}, name="user_update")
     * @Secure(roles="ROLE_MANAGER")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws DuplicateEntryException
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $this->verifyUserActionPrivileges($this->getUser(), $entity);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UserType(), $entity);
        $editForm->remove('password');
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $editForm->remove('groups');
            $editForm->remove('company');
        }
        $editForm->bind($request);

        try {
            if ($editForm->isValid()) {
                $data = $editForm->getData();

                if ($em->getRepository('YawmanTrainingBundle:User')->findOneBy(array('email' => $data->getEmail()))) {
                    $editForm->addError(new FormError('This email address is already in use.'));
                    throw new DuplicateEntryException('Email address must be unique');
                }

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
            }
        } catch (DuplicateEntryException $e) {
            
        } catch (\Exception $e) {
            
        }

        return $this->render('YawmanTrainingBundle:User:edit.html.twig', array('entity' => $entity, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()));
    }

    /**
     * Displays a form to edit the password for an existing User entity.
     * 
     * @Route("/{id}/edit-password", requirements={"id" = "\d+"}, name="user_edit_password")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editPasswordAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $this->verifyUserActionPrivileges($this->getUser(), $entity);

        $form = $this->createForm(new ChangePasswordType, new ChangePassword);
        $form->remove('current_password');

        return $this->render('YawmanTrainingBundle:User:edit-password.html.twig', array('entity' => $entity, 'form' => $form->createView()));
    }

    /**
     * Edits the password for an existing User entity.
     * 
     * @Route("/{id}/update-password", requirements={"_method" = "post", "id" = "\d+"}, name="user_update_password")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws PasswordConfirmationException
     */
    public function updatePasswordAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $this->verifyUserActionPrivileges($this->getUser(), $entity);

        $form = $this->createForm(new ChangePasswordType, new ChangePassword);
        $form->remove('current_password');
        $form->bind($request);

        try {
            if ($form->isValid()) {

                $data = $form->getData();

                if ($data->getPassword() != $data->getPasswordConfirm()) {
                    $form->addError(new FormError('New Password and Confirmation Password did not match'));
                    throw new PasswordConfirmationException('The new password did not match the confirmation password');
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
        } catch (PasswordConfirmationException $e) {
            
        } catch (\Exception $e) {
            
        }

        return $this->render('YawmanTrainingBundle:User:edit-password.html.twig', array('entity' => $entity, 'form' => $form->createView()));
    }

    /**
     * Deletes a User entity.
     * 
     * @Route("/{id}/delete", requirements={"_method" = "post", "id" = "\d+"}, name="user_delete")
     * @Secure(roles="ROLE_MANAGER")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        $user = $this->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        if ($user->getId() == $entity->getId()) {
            $this->get('session')->setFlash('info', 'You cannot delete your own account.');
            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }

        $this->verifyUserActionPrivileges($user, $entity);

        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * Provides a form used to delete a User entity
     * 
     * @param int $id
     * @return \Symfony\Component\Form\FormBuilder
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * Verifies the user has the appropriate privileges to modify the entity.
     * 
     * @param \Yawman\TrainingBundle\Entity\User $user
     * @param \Yawman\TrainingBundle\Entity\User $entity
     * @throws AccessDeniedException
     */
    private function verifyUserActionPrivileges(User $user, User $entity) {
        if (false === $this->get('security.context')->isGranted('ROLE_MANAGER')) {
            if ($user->getId() !== $entity->getId()) {
                throw new AccessDeniedException();
            }
        }

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            if ($user->getCompany() !== $entity->getCompany()) {
                throw new AccessDeniedException();
            }
        }
    }

}
