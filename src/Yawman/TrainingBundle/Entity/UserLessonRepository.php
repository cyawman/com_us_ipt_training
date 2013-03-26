<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Yawman\TrainingBundle\Entity\User;
use Yawman\TrainingBundle\Entity\Company;

class UserLessonRepository extends EntityRepository {
    
    /**
     * 
     * @param \Yawman\TrainingBundle\Entity\User $user
     * @return array
     */
    public function findAllUserLessonsByUser(User $user){
        
        $lessonplans = $user->getLessonplans();
        
        $userLessonsByLessonPlan = array();
        
        foreach($lessonplans as $plan){
            $userLessonsByLessonPlan[$plan->getId()]['name'] = $plan->getName();
            $lessons = $plan->getLessons();
            foreach($lessons as $lesson){
                $userLessonsByLessonPlan[$plan->getId()]['lessons'][$lesson->getId()]['id'] = $lesson->getId();
                $userLessonsByLessonPlan[$plan->getId()]['lessons'][$lesson->getId()]['name'] = $lesson->getName();
                $userLessonsByLessonPlan[$plan->getId()]['lessons'][$lesson->getId()]['status'] = 'Unavailable';
                $userLesson = $this->findOneBy(array('user' => $user, 'lesson' => $lesson));
                if($userLesson){
                    $userLessonsByLessonPlan[$plan->getId()]['lessons'][$lesson->getId()]['status'] = $userLesson->getStatus();
                }
            }
        }
        
        return $userLessonsByLessonPlan;
    }
    
    /**
     * 
     * @param \Yawman\TrainingBundle\Entity\Company $company
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findByCompanyUsers(Company $company, $orderBy = null, $limit = null, $offset = null){
        
        $userIds = $this->getEntityManager()->createQueryBuilder();
        $userIds->addSelect('distinct u.id');
        $userIds->from('YawmanTrainingBundle:User', 'u');
        $userIds->where($userIds->expr()->eq('u.company', $company->getId()));
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select('ul')->from('YawmanTrainingBundle:UserLesson', 'ul')
                ->where($qb->expr()->in('ul.user', $userIds->getDQL()))
                ->setMaxResults($limit)
                ->orderBy('ul.modifiedAt', 'DESC');
        
        return $qb->getQuery()->getResult();
        
    }
    
}
