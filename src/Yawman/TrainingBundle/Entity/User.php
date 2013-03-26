<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User
 * 
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Yawman\TrainingBundle\Entity\UserRepository")
 * 
 * @author Chris Yawman
 */
class User implements AdvancedUserInterface, \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;

    /**
     * @ORM\Column(name="has_logged_in", type="boolean")
     */
    private $hasLoggedIn = false;
    
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="modified_at", type="datetime")
     */
    protected $modifiedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="users")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     *
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity="UserLessonPlan", mappedBy="user")
     *
     */
    private $userLessonPlans;

    public function __construct() {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->groups = new ArrayCollection();
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
     * @inheritDoc
     */
    public function getUsername() {
        return $this->username;
    }
    
    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEmail() {
        return $this->email;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt() {
        return $this->salt;
    }
    
    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive() {
        return $this->isActive;
    }
    
    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }
    
    /**
     * Get hasLoggedIn
     * 
     * @return boolean
     */
    public function getHasLoggedIn() {
        return $this->hasLoggedIn;
    }

    /**
     * Set hasLoggedIn
     * 
     * @param boolean $hasLoggedIn
     */
    public function setHasLoggedIn($hasLoggedIn) {
        $this->hasLoggedIn = $hasLoggedIn;
    }

    /**
     * Set company
     *
     * @param \Yawman\TrainingBundle\Entity\Company $company
     * @return User
     */
    public function setCompany(\Yawman\TrainingBundle\Entity\Company $company = null) {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \Yawman\TrainingBundle\Entity\Company 
     */
    public function getCompany() {
        return $this->company;
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
     * @inheritDoc
     */
    public function eraseCredentials() {
        
    }

    /**
     * @inheritDoc
     */
    public function getRoles() {
        return $this->groups->toArray();
    }

    /**
     * @return boolean
     */
    public function isAccountNonExpired() {
        return true;
    }

    /**
     * @return boolean
     */
    public function isAccountNonLocked() {
        return true;
    }

    /**
     * @return boolean
     */
    public function isCredentialsNonExpired() {
        return true;
    }

    /**
     * @return boolean
     */
    public function isEnabled() {
        return $this->isActive;
    }

    /**
     * Add groups
     *
     * @param \Yawman\TrainingBundle\Entity\Group $groups
     * @return User
     */
    public function addGroup(\Yawman\TrainingBundle\Entity\Group $groups) {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Yawman\TrainingBundle\Entity\Group $groups
     */
    public function removeGroup(\Yawman\TrainingBundle\Entity\Group $groups) {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups() {
        return $this->groups;
    }
    
    /**
     * Add user lesson plans
     *
     * @param \Yawman\TrainingBundle\Entity\UserLessonPlan $userLessonPlans
     * @return User
     */
    public function addUserLessonPlans(\Yawman\TrainingBundle\Entity\UserLessonPlan $userLessonPlans) {
        $this->userLessonPlans[] = $userLessonPlans;

        return $this;
    }

    /**
     * Remove user LessonPlans
     *
     * @param \Yawman\TrainingBundle\Entity\UserLessonPlan $userLessonPlans
     */
    public function removeUserLessonplans(\Yawman\TrainingBundle\Entity\UserLessonPlan $userLessonPlans) {
        $this->userLessonPlans->removeElement($userLessonPlans);
    }

    /**
     * Get user lessonplans
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserLessonplans() {
        return $this->userLessonPlans;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize() {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
                $this->id,
                ) = unserialize($serialized);
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
        return sprintf('%s', $this->username);
    }
    
}