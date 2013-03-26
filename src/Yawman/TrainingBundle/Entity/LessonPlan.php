<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Lesson Plan
 *
 * @ORM\Table(name="lessonplan")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class LessonPlan {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=60, nullable=false)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;
    
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="modified_at", type="datetime")
     */
    protected $modifiedAt;

    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="UserLessonPlan", mappedBy="lessonPlan")
     */
    private $userLessonPlans;

    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="LessonPlanLesson", mappedBy="lessonPlan")
     */
    private $lessonPlanLessons;

    public function __construct() {
        $this->lessonPlanLessons = new ArrayCollection();
        $this->userLessonPlans = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return LessonPlan
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Get description
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set description
     * 
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
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
     * Add user lesson plans
     *
     * @param \Yawman\TrainingBundle\Entity\UserLessonPlan $userLessonPlans
     * @return LessonPlan
     */
    public function addUserLessonPlan(\Yawman\TrainingBundle\Entity\UserLessonPlan $userLessonPlans) {
        $this->userLessonPlans[] = $userLessonPlans;

        return $this;
    }

    /**
     * Remove user lesson plan
     *
     * @param \Yawman\TrainingBundle\Entity\UserLessonPlan $userLessonPlans
     */
    public function removeUserLessonPan(\Yawman\TrainingBundle\Entity\UserLessonPlan $userLessonPlans) {
        $this->userLessonPlans->removeElement($userLessonPlans);
    }

    /**
     * Get user lesson plans
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserLessonPlans() {
        return $this->userLessonPlans;
    }

    /**
     * Add lesson plan lessons
     *
     * @param \Yawman\TrainingBundle\Entity\LessonPlanLesson $lessonPlanLessons
     * @return LessonPlan
     */
    public function addLessonPlanLessons(\Yawman\TrainingBundle\Entity\LessonPlanLesson $lessonPlanLessons) {
        $this->lessonPlanLessons[] = $lessonPlanLesson;

        return $this;
    }

    /**
     * Remove lesson plan lessons
     *
     * @param \Yawman\TrainingBundle\Entity\LessonPlanLesson $lessonPlanLessons
     */
    public function removeLessonPlanLessons(\Yawman\TrainingBundle\Entity\LessonPlanLesson $lessonPlanLessons) {
        $this->lessonPlanLessons->removeElement($lessonPlanLessons);
    }

    /**
     * Get lesson plan lessons
     *
     * @return ArrayCollection 
     */
    public function getLessonPlanLessons() {
        return $this->lessonPlanLessons;
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
    
    /**
     * Return a string of the entity
     * 
     * @return string
     */
    public function __toString() {
        return sprintf('%s', $this->name);
    }

}