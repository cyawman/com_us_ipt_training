<?php

namespace Yawman\TrainingBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controls the management of the Dashboard
 * 
 * @author Chris Yawman
 * @Route("/dashboard")
 */
class DashboardController extends Controller {

    /**
     * Controls the logic around rendering the welcome and dashboard actions
     * 
     * @Route("/", name="dashboard")
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Renders the Welcome view for all Users
     * 
     * @Route("/welcome-user", name="welcome_user")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userWelcomeAction() {
        return $this->render('YawmanTrainingBundle:Dashboard:user-welcome.html.twig');
    }

    /**
     * Renders the Welcome view for all Users with ROLE_MANAGER
     * 
     * @Secure(roles="ROLE_MANAGER")
     * @Route("/welcome-manager", name="welcome_manager")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managementWelcomeAction() {
        return $this->render('YawmanTrainingBundle:Dashboard:management-welcome.html.twig');
    }

    /**
     * Renders the Dashboard view for all Users
     * 
     * @Route("/user-dashboard", name="user_dashboard")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userDashboardAction() {
        $em = $this->getDoctrine()->getManager();

        $userLessons = $em->getRepository('YawmanTrainingBundle:UserLesson')->findAllUserLessonsByUser($this->getUser());

        return $this->render('YawmanTrainingBundle:Dashboard:user-dashboard.html.twig', array('userLessons' => $userLessons));
    }

    /**
     * Renders the Dashboard view for all Users with ROLE_MANAGER
     * 
     * @Secure(roles="ROLE_MANAGER")
     * @Route("/manager-dashboard", name="manager_dashboard")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managementDashboardAction() {
        return $this->render('YawmanTrainingBundle:Dashboard:management-dashboard.html.twig');
    }

    /**
     * Renders the Dashboard view for all Users with ROLE_ADMIN
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/admin-dashboard", name="admin_dashboard")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminDashboardAction() {
        
        $em = $this->getDoctrine()->getManager();

        $lessonPlans = $em->getRepository('YawmanTrainingBundle:LessonPlan')->findAll();

        return $this->render('YawmanTrainingBundle:Dashboard:admin-dashboard.html.twig', array('lessonPlans' => $lessonPlans));
    }

}