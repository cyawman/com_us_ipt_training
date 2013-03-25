<?php

namespace Yawman\TrainingBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controls the management of User Lesson entities
 * 
 * @author Chris Yawman
 * @Route("/user-lesson")
 */
class UserLessonController extends Controller {
    
    /**
     * Fetch the UserLesson details
     * 
     * @Secure(roles="ROLE_USER")
     * @Route("/{lessonId}/status/{userId}", requirements={"lessonId" = "\d+", "userId" = "\d+"}, name="user_lesson_status")
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @param int $userId
     * @param int $lessonId
     */
    public function userLessonStatusAction($userId, $lessonId){
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('YawmanTrainingBundle:User')->find($userId);
        if(!$user){
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        $lesson = $em->getRepository('YawmanTrainingBundle:Lesson')->find($lessonId);
        if(!$lesson){
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }
        
        $userLesson = $em->getRepository('YawmanTrainingBundle:UserLesson')->findOneBy(array('user' => $user, 'lesson' => $lesson));
        
        return $this->render('YawmanTrainingBundle:UserLesson:status.html.twig', array('user_lesson' => $userLesson));
    }
    
}
