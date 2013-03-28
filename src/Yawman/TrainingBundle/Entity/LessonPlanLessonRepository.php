<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LessonPlanLessonRepository extends EntityRepository {

    /**
     * Return all Lessons not in a given Lesson Plan
     * 
     * @param type $id
     */
    public function findLessonsNotInLessonPlan($id) {

        $qb = $this->getEntityManager()->createQueryBuilder();

        $subQuery = $qb->select('lpl')
                ->from('YawmanTrainingBundle:LessonPlanLesson', 'lpl')
                ->where($qb->expr()->eq('lpl.lessonPlan', $id))
                ->getQuery()
                ->getResult();

        if (count($subQuery) > 0) {
            $nots = array();
            
            foreach ($subQuery as $lpl) {
                $nots[] = $lpl->getLesson()->getId();
            }
            
            $query = $qb->select('l')
                    ->from('YawmanTrainingBundle:Lesson', 'l')
                    ->where($qb->expr()->notIn('l', $nots))
                    ->getQuery()
                    ->getResult();

            return $query;
        }

        return $this->getEntityManager()->getRepository('YawmanTrainingBundle:Lesson')->findAll();
    }
    
}