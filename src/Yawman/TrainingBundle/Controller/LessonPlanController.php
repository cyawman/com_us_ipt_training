<?php

namespace Yawman\TrainingBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Yawman\TrainingBundle\Entity\LessonPlan;
use Yawman\TrainingBundle\Form\LessonPlanType;
use Yawman\TrainingBundle\Entity\LessonPlanLesson;
use Yawman\TrainingBundle\Entity\UserLessonPlan;

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
     * Lists all LessonPlan entities to choose from for adding a Lesson
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/choose-lesson-plan", name="lessonplan_choose_for_lesson")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function chooseLessonPlanAction(Request $request) {
        $form = $this->createFormBuilder()->add('lessonplan', 'entity', array('class' => 'YawmanTrainingBundle:LessonPlan', 'property' => 'name'))->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $data = $form->getData();
                return $this->redirect($this->generateUrl('lessonplan_edit_lessons', array('id' => $data['lessonplan']->getId())));
            }
        }

        return $this->render('YawmanTrainingBundle:LessonPlan:choose-lesson-plan.html.twig', array('form' => $form->createView()));
    }

    /**
     * Edits the Lessons in a Lesson Plan
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/edit-lessons", requirements={"id" = "\d+"}, name="lessonplan_edit_lessons")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editLessonsAction($id) {
        $em = $this->getDoctrine()->getManager();

        $lessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

        if (!$lessonPlan) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $lessonPlanLessons = $em->getRepository('YawmanTrainingBundle:LessonPlanLesson')->findBy(array('lessonPlan' => $lessonPlan), array('position' => 'ASC'));

        $lessonsNotInLessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlanLesson')->findLessonsNotInLessonPlan($lessonPlan->getId());

        $addLessonsForm = $this->createAddLessonsForm($lessonsNotInLessonPlan);

        $updateLessonPlanForm = $this->createUpdateLessonPositionsForm($lessonPlanLessons);

        return $this->render('YawmanTrainingBundle:LessonPlan:edit-lessons.html.twig', array('lesson_plan' => $lessonPlan, 'lesson_plan_lessons' => $lessonPlanLessons, 'lessons_not_in_lesson_plan' => $lessonsNotInLessonPlan, 'add_lessons_form' => $addLessonsForm->createView(), 'update_lessons_form' => $updateLessonPlanForm->createView()));
    }

    /**
     * Add a Lesson to a LessonPlan
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/add-lessons", requirements={"_method" = "post", "id" = "\d+"}, name="lessonplan_add_lessons")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     */
    public function addLessonAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $lessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

        if (!$lessonPlan) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $lessonPlanLessons = $em->getRepository('YawmanTrainingBundle:LessonPlanLesson')->findBy(array('lessonPlan' => $lessonPlan), array('position' => 'ASC'));

        $lessonsNotInLessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlanLesson')->findLessonsNotInLessonPlan($lessonPlan->getId());

        $addLessonsForm = $this->createAddLessonsForm($lessonsNotInLessonPlan);

        $addLessonsForm->bind($request);

        if ($addLessonsForm->isValid()) {
            $data = $addLessonsForm->getData();

            $totalLessonsInPlan = count($lessonPlan->getLessonPlanLessons());

            foreach ($data['lessons'] as $lesson) {
                $lessonPlanLesson = new LessonPlanLesson();
                $lessonPlanLesson->setLessonPlan($lessonPlan);
                $lessonPlanLesson->setLesson($lesson);
                $lessonPlanLesson->setPosition($totalLessonsInPlan++);

                $em->persist($lessonPlanLesson);
            }

            $em->flush();

            $this->get('session')->setFlash('success', 'The Lesson was successfully added to the Lesson Plan!');

            return $this->redirect($this->generateUrl('lessonplan_edit_lessons', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:LessonPlan:edit-lessons.html.twig', array('lesson_plan' => $lessonPlan, 'lesson_plan_lessons' => $lessonPlanLessons, 'lessons_not_in_lesson_plan' => $lessonsNotInLessonPlan, 'add_lessons_form' => $addLessonsForm->createView()));
    }

    /**
     * Update Lessons in LessonPlan
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/update-lessons", requirements={"_method" = "post", "id" = "\d+"}, name="lessonplan_update_lessons")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     */
    public function updateLessonAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $lessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

        if (!$lessonPlan) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $lessonPlanLessons = $em->getRepository('YawmanTrainingBundle:LessonPlanLesson')->findBy(array('lessonPlan' => $id), array('position' => 'ASC'));
        if (!$lessonPlanLessons) {
            throw $this->createNotFoundException('Unable to find LessonPlanLesson entity.');
        }

        $lessonsNotInLessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlanLesson')->findLessonsNotInLessonPlan($lessonPlan->getId());

        $addLessonsForm = $this->createAddLessonsForm($lessonsNotInLessonPlan);

        $updateLessonPlanForm = $this->createUpdateLessonPositionsForm($lessonPlanLessons);

        $updateLessonPlanForm->bind($request);

        if ($updateLessonPlanForm->isValid()) {
            $data = $updateLessonPlanForm->getData();
            foreach ($lessonPlanLessons as $lpl) {
                $lesson = $lpl->getLesson();
                $lpl->setPosition($data[strval($lesson->getId())]);
                $em->persist($lpl);
            }
            $em->flush();

            $this->repositionLessons($lessonPlan);

            $this->get('session')->setFlash('success', 'The Lesson Plan was successfully updated!');

            return $this->redirect($this->generateUrl('lessonplan_edit_lessons', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:LessonPlan:edit-lessons.html.twig', array('lesson_plan' => $id, 'lesson_plan_lessons' => $lessonPlanLessons, 'lessons_not_in_lesson_plan' => $lessonsNotInLessonPlan, 'add_lessons_form' => $addLessonsForm->createView(), 'update_lessons_form' => $updateLessonPlanForm->createView()));
    }

    /**
     * Removes Lesson from LessonPlan
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{lessonPlanId}/remove-lesson/{lessonId}", requirements={"lessonPlanId" = "\d+", "lessonId" = "\d+"}, name="lessonplan_remove_lesson")  
     * @param int $lessonPlanId
     * @param int $lessonId
     */
    public function removeLessonAction($lessonPlanId, $lessonId) {
        $em = $this->getDoctrine()->getManager();

        $lessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($lessonPlanId);
        if (!$lessonPlan) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $lesson = $em->getRepository('YawmanTrainingBundle:Lesson')->find($lessonId);
        if (!$lesson) {
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }

        $lessonPlanLesson = $em->getRepository('YawmanTrainingBundle:LessonPlanLesson')->findOneBy((array('lessonPlan' => $lessonPlan, 'lesson' => $lesson)));
        if (!$lessonPlanLesson) {
            throw $this->createNotFoundException('Unable to find LessonPlanLesson entity.');
        }

        $em->remove($lessonPlanLesson);
        $em->flush();

        $this->repositionLessons($lessonPlan);

        $this->get('session')->setFlash('success', 'The Lesson was successfully removed from the Lesson Plan!');

        return $this->redirect($this->generateUrl('lessonplan_edit_lessons', array('id' => $lessonPlanId)));
    }
    
    /**
     * Assign a LessonPlan to all Users in a Company
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{lessonPlanId}/assign-company-users/{companyId}", requirements={"lessonPlanId" = "\d+", "companyId" = "\d+"}, name="lessonplan_assign_company_users") 
     * @param int $companyId
     * @param int $lessonPlanId
     */
    public function assignCompanyUsersAction($lessonPlanId, $companyId){
        $em = $this->getDoctrine()->getEntityManager();
        
        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($companyId);
        if(!$company){
            throw $this->createNotFoundException('Unable to find Company entity.');
        }
        
        $lessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($lessonPlanId);
        if(!$lessonPlan){
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }
        
        foreach($company->getUsers() as $user){
            $userLessonPlan = $em->getRepository('YawmanTrainingBundle:UserLessonPlan')->findOneBy(array('user' => $user, 'lessonPlan' => $lessonPlan));
            if(!$userLessonPlan){
                $userLessonPlan = new UserLessonPlan();
                $userLessonPlan->setUser($user);
                $userLessonPlan->setLessonPlan($lessonPlan);
                $em->persist($userLessonPlan);
            }
        }
        
        $em->flush();
        
        $this->get('session')->setFlash('success', 'The Lesson Plan ('.$lessonPlan->getName().') was successfully added to all Users in '.$company->getName().'!');
        
        return $this->redirect($this->generateUrl('dashboard'));
        
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

    /**
     * Provides a form to add a Lesson to a Lesson Plan
     * 
     * @param array $lessonsNotInLessonPlan
     */
    private function createAddLessonsForm($lessonsNotInLessonPlan) {
        return $this->createFormBuilder()
                        ->add('lessons', 'entity', array('class' => 'YawmanTrainingBundle:Lesson', 'choices' => $lessonsNotInLessonPlan, 'property' => 'name', 'label' => ' ', 'multiple' => true))
                        ->getForm();
    }
    
    /**
     * Provides a form to update the Lesson position in a Lesson Plan
     * 
     * @param array $lessonPlanLessons
     * @return type
     */
    private function createUpdateLessonPositionsForm($lessonPlanLessons){
        $updateLessonPlanFormBuilder = $this->createFormBuilder();
        foreach ($lessonPlanLessons as $lessonPlanLesson) {
            $lesson = $lessonPlanLesson->getLesson();
            $updateLessonPlanFormBuilder->add(strval($lesson->getId()), 'integer', array('data' => $lessonPlanLesson->getPosition()));
        }

        return $updateLessonPlanFormBuilder->getForm();
    }

    /**
     * Repositions the Lessons in the LessonPlan
     * 
     * @param LessonPlanLesson $lessonPlanLesson
     */
    private function repositionLessons(LessonPlanLesson $lessonPlan) {
        $em = $this->getDoctrine()->getManager();

        $lessonPlanLessons = $em->getRepository('YawmanTrainingBundle:LessonPlanLesson')->findBy(array('lessonPlan' => $lessonPlan), array('position' => 'ASC'));
        $lessonPlanLessonPosition = 0;
        foreach ($lessonPlanLessons as $lpl) {
            $lpl->setPosition($lessonPlanLessonPosition);

            $em->persist($lpl);
            $lessonPlanLessonPosition++;
        }
        return $em->flush();
    }

}
