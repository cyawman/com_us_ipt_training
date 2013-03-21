<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LessonPlanLesson
 *
 * @ORM\Table(name="lessonplan_lesson")
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
}