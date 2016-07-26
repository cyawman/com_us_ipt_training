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
            foreach($lessonPlanLessons as $lessonPlanLesson) {
                $qb = $em->createQueryBuilder();
                $qb->delete('YawmanTrainingBundle:UserLesson', 'ul');
                $qb->where('ul.user = :user');
                $qb->andWhere('ul.lesson = :lesson');
                $qb->setParameter('user', $user);
                $qb->setParameter('lesson', $lessonPlanLesson->getLesson());
                
                $qb->getQuery()->execute();
            }
            
            
        }
    }    
}