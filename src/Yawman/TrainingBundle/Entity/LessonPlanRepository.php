<?php


namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Yawman\TrainingBundle\Entity\Company;

class LessonPlanRepository extends EntityRepository {
    
    public function findLessonPlansNotWithCompany(Company $company){
        
        $companyLessonPlans = $company->getLessonPlans();
        
        $lessonPlanIds = array();
        foreach($companyLessonPlans as $lessonPlan){
            $lessonPlanIds[] = $lessonPlan->getId();
        }
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select('lp')->from('YawmanTrainingBundle:LessonPlan', 'lp');
        if(count($lessonPlanIds) > 0){
            $qb->where($qb->expr()->notIn('lp.id', $lessonPlanIds));
        }
        
        return $qb->getQuery()->getResult();
    }
    
}