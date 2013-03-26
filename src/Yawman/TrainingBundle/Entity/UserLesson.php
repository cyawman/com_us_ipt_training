<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Yawman\TrainingBundle\Entity\User;
use Yawman\TrainingBundle\Entity\Lesson;

/**
 * UserLesson
 *
 * @ORM\Table(name="user_lesson")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="modified_at", type="datetime")
     */
    protected $modifiedAt;

    /**
     * Set user
     *
     * @param \Yawman\TrainingBundle\Entity\User $user
     * @return UserLesson
     */
    public function setUser(User $user)
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
    public function setLesson(Lesson $lesson)
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