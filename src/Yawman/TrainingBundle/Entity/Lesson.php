<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Lesson
 *
 * @ORM\Table(name="lesson")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Lesson {

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
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    private $path;

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
     * @ORM\OneToMany(targetEntity="LessonPlanLesson", mappedBy="lesson")
     */
    private $lessonPlanLessons;

    /**
     * Constructor
     */
    public function __construct() {
        $this->lessonPlanLessons = new ArrayCollection();
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
     * @return Lesson
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
     * Get path
     * 
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set path
     * 
     * @param string $path
     */
    public function setPath($path) {
        $this->path = $path;
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
     * Add lesson plan lessons
     *
     * @param \Yawman\TrainingBundle\Entity\LessonPlanLesson $lessonPlanLessons
     * @return Lesson
     */
    public function addLessonPlanLessons(\Yawman\TrainingBundle\Entity\LessonPlanLesson $lessonPlanLessons) {
        $this->lessonPlanLessons[] = $lessonPlanLessons;

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
     * Generates the URL to the Lesson using the Lesson.path
     * 
     * @return string
     */
    public function generateLessonUrl() {
        return '/uploads/' . $this->path . '/presentation.html';
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