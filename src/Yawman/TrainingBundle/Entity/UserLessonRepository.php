<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Yawman\TrainingBundle\Entity\User;

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
    
}
