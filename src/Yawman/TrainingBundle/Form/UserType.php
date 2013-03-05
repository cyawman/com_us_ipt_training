<?php

namespace Yawman\TrainingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('salt')
            ->add('password')
            ->add('email')
            ->add('isActive')
            ->add('hasLoggedIn')
            ->add('created_at')
            ->add('modified_at')
            ->add('groups')
            ->add('lessonplans')
            ->add('company')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yawman\TrainingBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'yawman_trainingbundle_usertype';
    }
}
