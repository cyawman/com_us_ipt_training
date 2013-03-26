<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LessonPlanLesson
 *
 * @ORM\Table(name="lessonplan_lesson")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Yawman\TrainingBundle\Entity\LessonPlanLessonRepository")
 */
class LessonPlanLesson {
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="LessonPlan", inversedBy="lessonPlanLessons")
     */
    private $lessonPlan;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="lessonPlanLessons")
     */
    private $lesson;
    
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
     * Get the LessonPlan entity
     * 
     * @return LessonPlan
     */
    public function getLessonPlan() {
        return $this->lessonPlan;
    }

    /**
     * Set the LessonPlan entity
     * 
     * @param \Yawman\TrainingBundle\Entity\LessonPlan $lessonPlan
     */
    public function setLessonPlan(LessonPlan $lessonPlan) {
        $this->lessonPlan = $lessonPlan;
    }

    /**
     * Get the Lesson entity
     * 
     * @return Lesson
     */
    public function getLesson() {
        return $this->lesson;
    }

    /**
     * Sets the Lesson entity
     * 
     * @param \Yawman\TrainingBundle\Entity\Lesson $lesson
     */
    public function setLesson(Lesson $lesson) {
        $this->lesson = $lesson;
    }

    /**
     * Gets the position
     * 
     * @return int
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Sets the position
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