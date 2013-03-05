<?php

namespace Yawman\TrainingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Yawman\TrainingBundle\Entity\Lesson;
use Yawman\TrainingBundle\Form\LessonType;

class LessonController extends Controller {

    /**
     * Lists all Lesson entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('YawmanTrainingBundle:Lesson')->findAll();

        return $this->render('YawmanTrainingBundle:Lesson:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Lesson entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Lesson')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Lesson:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new Lesson entity.
     *
     */
    public function newAction() {
        $entity = new Lesson();
        $form = $this->createForm(new LessonType(), $entity);

        return $this->render('YawmanTrainingBundle:Lesson:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Lesson entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Lesson();
        $form = $this->createForm(new LessonType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lesson_show', array('id' => $entity->getId())));
        }

        return $this->render('YawmanTrainingBundle:Lesson:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Lesson entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Lesson')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }

        $editForm = $this->createForm(new LessonType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Lesson:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Lesson entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Lesson')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new LessonType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lesson_edit', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:Lesson:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Lesson entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('YawmanTrainingBundle:Lesson')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Lesson entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('lesson'));
    }

    private function CreateDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
