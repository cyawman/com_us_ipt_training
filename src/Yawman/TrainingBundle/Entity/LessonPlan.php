<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Lesson Plan
 *
 * @ORM\Table(name="lessonplan")
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
     * @var ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="User", mappedBy="lessonplans")
     */
    private $users;

    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="LessonPlanLesson", mappedBy="lesson")
     */
    private $lessons;

    public function __construct() {
        $this->lessons = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * Add users
     *
     * @param \Yawman\TrainingBundle\Entity\User $users
     * @return LessonPlan
     */
    public function addUser(\Yawman\TrainingBundle\Entity\User $users) {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Yawman\TrainingBundle\Entity\User $users
     */
    public function removeUser(\Yawman\TrainingBundle\Entity\User $users) {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Add lessons
     *
     * @param \Yawman\TrainingBundle\Entity\LessonPlanLesson $lessons
     * @return LessonPlan
     */
    public function addLesson(\Yawman\TrainingBundle\Entity\LessonPlanLesson $lessons) {
        $this->lessons[] = $lessons;

        return $this;
    }

    /**
     * Remove lessons
     *
     * @param \Yawman\TrainingBundle\Entity\LessonPlanLesson $lessons
     */
    public function removeLesson(\Yawman\TrainingBundle\Entity\LessonPlanLesson $lessons) {
        $this->lessons->removeElement($lessons);
    }

    /**
     * Get lessons
     *
     * @return ArrayCollection 
     */
    public function getLessons() {
        return $this->lessons;
    }

    public function __toString() {
        return sprintf('%s', $this->name);
    }

}