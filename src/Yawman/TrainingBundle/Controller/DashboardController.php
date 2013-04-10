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
            $response = $this->forward('YawmanTrainingBundle:Dashboard:applicationDashboard');
        } else if ($this->get('security.context')->isGranted('ROLE_MANAGER')) {
            if ($hasLoggedIn) {
                $response = $this->forward('YawmanTrainingBundle:Dashboard:companyDashboard', array("id" => $user->getCompany()->getId()) );
            } else {
                $response = $this->forward('YawmanTrainingBundle:Dashboard:managementWelcome');
            }
        } else {
            if ($hasLoggedIn) {
                $response = $this->forward('YawmanTrainingBundle:Dashboard:userDashboard', array("id" => $user->getId()));
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
     * @Route("/user/{id}", requirements={"id" = "\d+"}, name="user_dashboard")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userDashboardAction($id) {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('YawmanTrainingBundle:User')->find($id);
        if(!$user){
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        if (false === $this->get('security.context')->isGranted('ROLE_MANAGER')) {
            if ($user !== $this->getUser()) {
                throw new AccessDeniedException();
            }
        }
        
        return $this->render('YawmanTrainingBundle:Dashboard:user-dashboard.html.twig', array('user' => $user));
    }

    /**
     * Renders the Dashboard view for all Users with ROLE_MANAGER
     * 
     * @Secure(roles="ROLE_MANAGER")
     * @Route("/company/{id}", requirements={"id" = "\d+"}, name="company_dashboard")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function companyDashboardAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($id);
        if(!$company){
            throw $this->createNotFoundException('Unable to find Company entity.');
        }
        
        $isCompanyUser = ($company == $this->getUser()->getCompany()) ? true:false;
        
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            if (!$isCompanyUser) {
                throw new AccessDeniedException();
            }
        }
        
        $recentUserActivity = $em->getRepository('YawmanTrainingBundle:UserLesson')->findByCompanyUsers($company);
        
        return $this->render('YawmanTrainingBundle:Dashboard:company-dashboard.html.twig', array('recentUserActivity' => $recentUserActivity, 'user' => $this->getUser(), 'isCompanyUser' => $isCompanyUser, 'company' => $company));
    }

    /**
     * Renders the Dashboard view for all Users with ROLE_ADMIN
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/application", name="application_dashboard")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function applicationDashboardAction() {
        $em = $this->getDoctrine()->getManager();
        
        $recentUserActivity = $em->getRepository('YawmanTrainingBundle:UserLesson')->findBy(array(), null, 5);
        
        $companies = $em->getRepository('YawmanTrainingBundle:Company')->findBy(array(), null, 5);

        $lessonPlans = $em->getRepository('YawmanTrainingBundle:LessonPlan')->findBy(array(), null, 5);

        return $this->render('YawmanTrainingBundle:Dashboard:application-dashboard.html.twig', array('recentUserActivity' => $recentUserActivity, 'companies' => $companies , 'lessonPlans' => $lessonPlans));
    }
}