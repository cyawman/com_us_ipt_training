<?php

namespace Yawman\TrainingBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

use Yawman\TrainingBundle\Entity\User;

class Registration {

    protected $email;
    
    protected $password;
    
    protected $passwordConfirm;
    
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
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