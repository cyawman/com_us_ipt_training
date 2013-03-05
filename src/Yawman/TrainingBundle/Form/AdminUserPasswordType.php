<?php

namespace Yawman\TrainingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminUserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'password',
            'password',
            array('property_path' => 'password')
        );
        
        $builder->add(
            'password_confirm',
            'password',
            array('property_path' => 'passwordConfirm')
        );
    }

    public function getName()
    {
        return 'yawman_trainingbundle_userpasswordtype';
    }
}
