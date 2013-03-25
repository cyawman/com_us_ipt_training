<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserLessonPlan
 *
 * @ORM\Table(name="user_lessonplan")
 * @ORM\Entity(repositoryClass="Yawman\TrainingBundle\Entity\UserLessonPlanRepository")
 */
class UserLessonPlan {

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userLessonPlans")
     */
    private $user;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="LessonPlan", inversedBy="userLessonPlans")
     */
    private $lessonPlan;
    
    /** 
     * @ORM\Column(type="integer") 
     */
    private $position = 0;
    
    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getLessonPlan() {
        return $this->lessonPlan;
    }

    public function setLessonPlan($lessonPlan) {
        $this->lessonPlan = $lessonPlan;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position) {
        $this->position = $position;
    }
    
}