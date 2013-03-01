<?php

namespace Yawman\TrainingBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

use Yawman\TrainingBundle\Entity\User;

class ChangePassword {

    protected $currentPassword;
    
    protected $password;
    
    protected $passwordConfirm;
    
    public function getCurrentPassword() {
        return $this->currentPassword;
    }

    public function setCurrentPassword($currentPassword) {
        $this->currentPassword = $currentPassword;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setPasswordConfirm($passwordConfirm){
        $this->passwordConfirm = $passwordConfirm;
    }
    
    public function getPasswordConfirm(){
        return $this->passwordConfirm;
    }
}