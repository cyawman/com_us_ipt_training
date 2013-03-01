<?php

namespace Yawman\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Yawman\TrainingBundle\Entity\UserRepository")
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
    private $isActive;

    /**
     * @ORM\Column(name="has_logged_in", type="boolean")
     */
    private $hasLoggedIn = false;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $modified_at;

    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     *
     */
    private $groups;

    /**
     * @ORM\ManyToMany(targetEntity="LessonPlan", inversedBy="users")
     *
     */
    private $lessonplans;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="users")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    public function __construct() {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->groups = new ArrayCollection();
        $this->lessonplans = new ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
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

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return $this->isActive;
    }

    public function __toString() {
        return sprintf('%s', $this->username);
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
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive() {
        return $this->isActive;
    }
    
    public function getHasLoggedIn() {
        return $this->hasLoggedIn;
    }

    public function setHasLoggedIn($hasLoggedIn) {
        $this->hasLoggedIn = $hasLoggedIn;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    public function getModifiedAt() {
        return $this->modified_at;
    }

    public function setModifiedAt($modified_at) {
        $this->modified_at = $modified_at;
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
     * Add lessonplans
     *
     * @param \Yawman\TrainingBundle\Entity\LessonPlan $lessonplans
     * @return User
     */
    public function addLessonplan(\Yawman\TrainingBundle\Entity\LessonPlan $lessonplans) {
        $this->lessonplans[] = $lessonplans;

        return $this;
    }

    /**
     * Remove lessonplans
     *
     * @param \Yawman\TrainingBundle\Entity\LessonPlan $lessonplans
     */
    public function removeLessonplan(\Yawman\TrainingBundle\Entity\LessonPlan $lessonplans) {
        $this->plans->removeElement($lessonplans);
    }

    /**
     * Get lessonplans
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLessonplans() {
        return $this->lessonplans;
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