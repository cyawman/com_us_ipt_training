<?php

namespace Yawman\TrainingBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controls the management of User Lesson entities
 * 
 * @author Chris Yawman
 * @Route("/user-lesson-plan")
 */
class UserLessonPlanController extends Controller {
    
    /**
     * Fetch the UserLessonPlan details
     * 
     * @Secure(roles="ROLE_USER")
     * @Route("/{userId}", requirements={"userId" = "\d+"}, name="user_lesson_plans_for_user")
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @param int $userId
     */
    public function lessonPlansForUserAction($userId){
        $em = $this->getDoctrine()->getEntityManager();
        
        $user = $em->getRepository('YawmanTrainingBundle:User')->find($userId);
        if(!$user){
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        $userLessonPlans = $em->getRepository('YawmanTrainingBundle:UserLessonPlan')->findBy(array('user' => $user));
        
        return $this->render('YawmanTrainingBundle:UserLessonPlan:_user-lesson-plans-for-user.html.twig', array('user' => $user, 'userLessonPlans' => $userLessonPlans));
    }
    
}
