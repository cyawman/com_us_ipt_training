<?php

namespace Yawman\TrainingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Yawman\TrainingBundle\Form\UserType;

class DashboardController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $hasLoggedIn = $user->getHasLoggedIn();

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $response = $this->forward('YawmanTrainingBundle:Dashboard:adminDashboard');
        } else if ($this->get('security.context')->isGranted('ROLE_MANAGER')) {
            if ($hasLoggedIn) {
                $response = $this->forward('YawmanTrainingBundle:Dashboard:managementDashboard');
            } else {
                $response = $this->forward('YawmanTrainingBundle:Dashboard:managementWelcome');
            }
        } else {
            if ($hasLoggedIn) {
                $response = $this->forward('YawmanTrainingBundle:Dashboard:userDashboard');
            } else {
                $response = $this->forward('YawmanTrainingBundle:Dashboard:userWelcome');
            }
        }

        if (!$hasLoggedIn) {
            $user->setHasLoggedIn(true);
            $em->persist($user);
            $em->flush();
        }

        return $response;
    }

    public function userWelcomeAction() {
        return $this->render('YawmanTrainingBundle:Dashboard:user-welcome.html.twig');
    }

    public function managementWelcomeAction() {
        if (false === $this->get('security.context')->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }
        return $this->render('YawmanTrainingBundle:Dashboard:management-welcome.html.twig');
    }

    public function userDashboardAction() {
        $em = $this->getDoctrine()->getManager();

        $userLessons = $em->getRepository('YawmanTrainingBundle:UserLesson')->findAllUserLessonsByUser($this->getUser());

        return $this->render('YawmanTrainingBundle:Dashboard:user-dashboard.html.twig', array('userLessons' => $userLessons));
    }

    public function managementDashboardAction() {
        return $this->render('YawmanTrainingBundle:Dashboard:management-dashboard.html.twig');
    }

    public function adminDashboardAction() {
        return $this->render('YawmanTrainingBundle:Dashboard:admin-dashboard.html.twig');
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function userEditAction() {

        $entity = $this->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);

        return $this->render('YawmanTrainingBundle:Dashboard:user-edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Edits an existing User entity.
     *
     */
    public function userUpdateAction(Request $request) {

        $entity = $this->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dashboard_user_edit', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:Dashboard:user-edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

}

