<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserLessonPlanRepository extends EntityRepository {
    
    /**
     * 
     * @param User $user
     * @param LessonPlan $lessonPlan
     */
    public function removeUserFromLessonPlan(User $user, LessonPlan $lessonPlan) {        
        $this->removeAllUserLessonByUserAndLessonPlan($user, $lessonPlan);
        
        $em = $this->getEntityManager();
        
        $userLessonPlan = $em->getRepository('YawmanTrainingBundle:UserLessonPlan')->findOneBy(array('user' => $user, 'lessonPlan' => $lessonPlan));            
        if ($userLessonPlan) {
            $em->remove($userLessonPlan);
        }
    }
    
    /**
     * 
     * @param User $user
     * @param LessonPlan $lessonPlan
     */
    public function removeAllUserLessonByUserAndLessonPlan(User $user, LessonPlan $lessonPlan) {
        $em = $this->getEntityManager();
        
        $lessonPlanLessons = $em->getRepository('YawmanTrainingBundle:LessonPlanLesson')->findAll(array('lessonPlan' => $lessonPlan));
        if ($lessonPlanLessons) {
            $lessonIds = [];
            foreach($lessonPlanLessons as $lessonPlanLesson) {
                $lessonIds[] = $lessonPlanLesson->getLesson()->getId();
            }
            
            $q = $em->createQuery('delete from UserLesson ul where ul.user_id = :user_id AND ul.lesson_id IN (:lesson_ids)')
                    ->setParameter('user_id', $user->getId())
                    ->setParameter('lesson_ids', $lessonIds);
            $q->execute();
        }               
    }    
}