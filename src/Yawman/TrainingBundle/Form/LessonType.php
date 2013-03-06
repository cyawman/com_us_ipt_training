<?php

namespace Yawman\TrainingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Yawman\TrainingBundle\Utility\LessonPathExplorerUtility;

class LessonType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('description', 'textarea', array('max_length' => 255))
                ->add('path', 'choice', array('choices' => $this->getLessonChoices()));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Yawman\TrainingBundle\Entity\Lesson'
        ));
    }

    public function getLessonChoices() {
        $lessonExplorer = new LessonPathExplorerUtility();
        $lessonExplorer->setPath($_SERVER['DOCUMENT_ROOT'] . '/uploads');
        $lessonExplorer->fetchPath();
        return $lessonExplorer->getDirectories();
    }

    public function getName() {
        return 'yawman_trainingbundle_lessontype';
    }

}
