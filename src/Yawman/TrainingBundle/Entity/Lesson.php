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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="LessonPlan", mappedBy="lessons")
     */
    private $plans;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->plans = new ArrayCollection();
    }
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Lesson
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add plans
     *
     * @param \Yawman\TrainingBundle\Entity\LessonPlan $plans
     * @return Lesson
     */
    public function addPlan(\Yawman\TrainingBundle\Entity\LessonPlan $plans)
    {
        $this->plans[] = $plans;
    
        return $this;
    }

    /**
     * Remove plans
     *
     * @param \Yawman\TrainingBundle\Entity\LessonPlan $plans
     */
    public function removePlan(\Yawman\TrainingBundle\Entity\LessonPlan $plans)
    {
        $this->plans->removeElement($plans);
    }

    /**
     * Get plans
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlans()
    {
        return $this->plans;
    }
    
    public function __toString()
    {
        return sprintf('%s', $this->name);
    }
}