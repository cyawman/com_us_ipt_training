<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserLesson
 *
 * @ORM\Table(name="user_lesson")
 * @ORM\Entity(repositoryClass="Yawman\TrainingBundle\Entity\UserLessonRepository")
 */
class UserLesson {
    
    const PASS = 'pass';
    const FAIL = 'fail';
    const INCOMPLETE = 'incomplete';
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Lesson")
     */
    private $lesson;
    
    /** 
     * @ORM\Column(type="string", columnDefinition="ENUM('pass', 'fail', 'incomplete')") 
     */
    private $status;
    
    

    /**
     * Set status
     *
     * @param string $status
     * @return UserLesson
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param \Yawman\TrainingBundle\Entity\User $user
     * @return UserLesson
     */
    public function setUser(\Yawman\TrainingBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Yawman\TrainingBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set lesson
     *
     * @param \Yawman\TrainingBundle\Entity\Lesson $lesson
     * @return UserLesson
     */
    public function setLesson(\Yawman\TrainingBundle\Entity\Lesson $lesson)
    {
        $this->lesson = $lesson;
    
        return $this;
    }

    /**
     * Get lesson
     *
     * @return \Yawman\TrainingBundle\Entity\Lesson 
     */
    public function getLesson()
    {
        return $this->lesson;
    }
}