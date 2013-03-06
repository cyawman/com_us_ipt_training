<?php

namespace Yawman\TrainingBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Yawman\TrainingBundle\Entity\LessonPlan;
use Yawman\TrainingBundle\Form\LessonPlanType;

/**
 * Controls the management of LessonPlan entities
 * 
 * @author chris Yawman
 * @Route("/lesson-plan")
 */
class LessonPlanController extends Controller {

    /**
     * Lists all LessonPlan entities
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/", name="lessonplan")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('YawmanTrainingBundle:LessonPlan')->findAll();

        return $this->render('YawmanTrainingBundle:LessonPlan:index.html.twig', array('entities' => $entities));
    }

    /**
     * Displays a LessonPlan entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/show", requirements={"id" = "\d+"}, name="lessonplan_show")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:LessonPlan:show.html.twig', array('entity' => $entity, 'delete_form' => $deleteForm->createView()));
    }

    /**
     * Creates a new LessonPlan entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/new", name="lessonplan_new")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction() {
        $entity = new LessonPlan();
        $form = $this->createForm(new LessonPlanType(), $entity);

        return $this->render('YawmanTrainingBundle:LessonPlan:new.html.twig', array('entity' => $entity, 'form' => $form->createView()));
    }

    /**
     * Creates a new LessonPlan entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/create", requirements={"_method" = "post"}, name="lessonplan_create")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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

        return $this->render('YawmanTrainingBundle:LessonPlan:new.html.twig', array('entity' => $entity, 'form' => $form->createView()));
    }

    /**
     * Provides a form to edit a LessonPlan entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="lessonplan_edit")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $editForm = $this->createForm(new LessonPlanType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:LessonPlan:edit.html.twig', array('entity' => $entity, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()));
    }

    /**
     * Provides a form to edit a LessonPlan entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/update", requirements={"_method" = "post", "id" = "\d+"}, name="lessonplan_update")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
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

        return $this->render('YawmanTrainingBundle:LessonPlan:edit.html.twig', array('entity' => $entity, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()));
    }

    /**
     * Deletes a LessonPlan entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/delete", requirements={"_method" = "post", "id" = "\d+"}, name="lessonplan_delete")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
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

    /**
     * Provides a form to delete a LessonPlan entity
     * 
     * @param int $id
     * @return \Symfony\Component\Form\FormBuilder
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm();
    }
}
