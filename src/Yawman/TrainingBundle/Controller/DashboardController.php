<?php

namespace Yawman\TrainingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}