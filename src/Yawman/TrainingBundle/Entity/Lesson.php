<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Lesson
 *
 * @ORM\Table(name="lesson")
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

    public function __toString() {
        return sprintf('%s', $this->name);
    }

}