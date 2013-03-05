<?php

namespace Yawman\TrainingBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class AdminUserType extends UserType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('username');
        $builder->remove('salt');
        $builder->remove('isActive');
        $builder->remove('hasLoggedIn');
        $builder->remove('created_at');
        $builder->remove('modified_at');
        $builder->remove('lessonplans');
    }
    
}