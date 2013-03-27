<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Company {

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
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="modified_at", type="datetime")
     */
    protected $modifiedAt;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="company", cascade={"remove"})
     */
    private $users;
    
    /**
     * @ORM\ManyToMany(targetEntity="LessonPlan", inversedBy="company")
     */
    private $lessonPlans;

    public function __construct() {
        $this->users = new ArrayCollection();
        $this->lessonPlans = new ArrayCollection();
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
     * @return Company
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
     * Add users
     *
     * @param \Yawman\TrainingBundle\Entity\User $users
     * @return Company
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
     * Add lessonPlans
     *
     * @param \Yawman\TrainingBundle\Entity\LessonPlan $lessonPlan
     * @return Company
     */
    public function addLessonPlan(\Yawman\TrainingBundle\Entity\LessonPlan $lessonPlan) {
        $this->lessonPlans[] = $lessonPlan;

        return $this;
    }

    /**
     * Remove lessonPlan
     *
     * @param \Yawman\TrainingBundle\Entity\LessonPlan $lessonPlan
     */
    public function removeLessonPlan(\Yawman\TrainingBundle\Entity\LessonPlan $lessonPlan) {
        $this->lessonPlans->removeElement($lessonPlan);
    }

    /**
     * Get lessonPlans
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLessonPlans() {
        return $this->lessonPlans;
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
     * Return string of entity
     * 
     * @return string
     */
    public function __toString() {
        return sprintf('%s', $this->name);
    }

}