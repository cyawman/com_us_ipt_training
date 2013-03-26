<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Yawman\TrainingBundle\Entity\LessonPlan;
use Yawman\TrainingBundle\Entity\User;

/**
 * UserLessonPlan
 *
 * @ORM\Table(name="user_lessonplan")
 * @ORM\HasLifecycleCallbacks
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
    
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="modified_at", type="datetime")
     */
    protected $modifiedAt;
    
    /**
     * Get user
     * 
     * @return \Yawman\TrainingBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set user
     * 
     * @param \Yawman\TrainingBundle\Entity\User $user
     */
    public function setUser(User $user) {
        $this->user = $user;
    }

    /**
     * Get lessonPlan
     * 
     * @return Yawman\TrainingBundle\Entity\LessonPlan
     */
    public function getLessonPlan() {
        return $this->lessonPlan;
    }

    /**
     * Set lessonPlan
     * 
     * @param \Yawman\TrainingBundle\Entity\LessonPlan $lessonPlan
     */
    public function setLessonPlan(LessonPlan $lessonPlan) {
        $this->lessonPlan = $lessonPlan;
    }

    /**
     * Get position
     * 
     * @return int
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Set position
     * 
     * @param int $position
     */
    public function setPosition($position) {
        $this->position = $position;
    }
    
    /**
     * Get createdAt
     * 
     * @return DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     * 
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * Get modifiedAt
     * 
     * @return DateTime
     */
    public function getModifiedAt() {
        return $this->modifiedAt;
    }

    /**
     * Set modifiedAt
     * 
     * @param DateTime $modifiedAt
     */
    public function setModifiedAt($modifiedAt) {
        $this->modifiedAt = $modifiedAt;
    }
    
    /**
     * Now we tell doctrine that before we persist or update we call the updatedTimestamps() function.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps() {
        $this->setModifiedAt(new \DateTime(date('Y-m-d H:i:s')));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
    
}