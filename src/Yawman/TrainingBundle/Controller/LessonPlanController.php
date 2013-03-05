<?php

namespace Yawman\TrainingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Yawman\TrainingBundle\Entity\LessonPlan;
use Yawman\TrainingBundle\Form\LessonPlanType;

class LessonPlanController extends Controller {

    /**
     * Lists all LessonPlan entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('YawmanTrainingBundle:LessonPlan')->findAll();

        return $this->render('YawmanTrainingBundle:LessonPlan:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a LessonPlan entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:LessonPlan:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new LessonPlan entity.
     *
     */
    public function newAction() {
        $entity = new LessonPlan();
        $form = $this->createForm(new LessonPlanType(), $entity);

        return $this->render('YawmanTrainingBundle:LessonPlan:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new LessonPlan entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new LessonPlan();
        $form = $this->createForm(new LessonPlanType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lessonplan_show', array('id' => $entity->getId())));
        }

        return $this->render('YawmanTrainingBundle:LessonPlan:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing LessonPlan entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $editForm = $this->createForm(new LessonPlanType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:LessonPlan:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing LessonPlan entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new LessonPlanType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lessonplan_edit', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:LessonPlan:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a LessonPlan entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find LessonPlan entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('lessonplan'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
