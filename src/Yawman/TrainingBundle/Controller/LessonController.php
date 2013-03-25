<?php

namespace Yawman\TrainingBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Yawman\TrainingBundle\Entity\Lesson;
use Yawman\TrainingBundle\Form\LessonType;
use Yawman\TrainingBundle\Entity\UserLesson;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Controls the management of Lesson entities
 * 
 * @author Chris Yawman
 * @Route("/lesson")
 */
class LessonController extends Controller {

    /**
     * Lists the Lesson entities
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/", name="lesson")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('YawmanTrainingBundle:Lesson')->findAll();

        return $this->render('YawmanTrainingBundle:Lesson:index.html.twig', array('entities' => $entities));
    }

    /**
     * Displays a Lesson entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/show", requirements={"id" = "\d+"}, name="lesson_show")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Lesson')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Lesson:show.html.twig', array('entity' => $entity, 'delete_form' => $deleteForm->createView()));
    }

    /**
     * Creates a new Lesson entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/new", name="lesson_new")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction() {
        $entity = new Lesson();
        $form = $this->createForm(new LessonType(), $entity);

        return $this->render('YawmanTrainingBundle:Lesson:new.html.twig', array('entity' => $entity, 'form' => $form->createView()));
    }

    /**
     * Creates a new Lesson entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/create", requirements={"_method" = "post"}, name="lesson_create")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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

        return $this->render('YawmanTrainingBundle:Lesson:new.html.twig', array('entity' => $entity, 'form' => $form->createView()));
    }

    /**
     * Provides a form to edit a Lesson entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="lesson_edit")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Lesson')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }

        $editForm = $this->createForm(new LessonType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Lesson:edit.html.twig', array('entity' => $entity, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()));
    }

    /**
     * Provides a form to edit a Lesson entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/update", requirements={"_method" = "post", "id" = "\d+"}, name="lesson_update")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
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

        return $this->render('YawmanTrainingBundle:Lesson:edit.html.twig', array('entity' => $entity, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()));
    }

    /**
     * Deletes a Lesson entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/delete", requirements={"_method" = "post", "id" = "\d+"}, name="lesson_delete")
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
            $entity = $em->getRepository('YawmanTrainingBundle:Lesson')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Lesson entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('lesson'));
    }
    
    /**
     * Save the Lesson Plan and Lesson information in the Users session before playing the Lesson.
     * 
     * @Secure(roles="ROLE_USER")
     * @Route("/{lessonPlanId}/play/{lessonId}", name="lesson_play")
     * @param int $lessonPlanId
     * @param int $lessonId
     */
    public function playLessonAction($lessonPlanId, $lessonId){
        $em = $this->getDoctrine()->getManager();
        
        $lessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($lessonPlanId);
        if(!$lessonPlan){
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }
        
        $lesson = $em->getRepository('YawmanTrainingBundle:Lesson')->find($lessonId);
        if(!$lesson){
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }
        
        $userLessonPlan = $em->getRepository('YawmanTrainingBundle:UserLessonPlan')->findOneBy(array('lessonPlan' => $lessonPlan, 'user' => $this->getUser()));
        if(!$userLessonPlan){
            throw new AccessDeniedException();
        }
        
        /**
         * Create or Update the UserLesson.status
         */
        $userLesson = $em->getRepository('YawmanTrainingBundle:UserLesson')->findOneBy(array('user' => $this->getUser(), 'lesson' => $lesson));
        if(!$userLesson){
            $userLesson = new UserLesson();
            $userLesson->setUser($this->getUser());
            $userLesson->setLesson($lesson);
        }
        $userLesson->setStatus(UserLesson::INCOMPLETE);
        
        $em->persist($userLesson);
        
        $em->flush();
        
        $session = $this->get('session');
        $session->set('activeLesson', array('lessonPlanId' => $lessonPlanId, 'lessonId' => $lessonId));
        
        /**
         * @todo Redirect the user to the Lesson
         */
        return $this->redirect($lesson->generateLessonUrl());
    }
    
    /**
     * Passes a User Lesson
     * 
     * @Secure(roles="ROLE_USER")
     * @Route("/pass", name="lesson_pass")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function passAction(){
        $session = $this->get('session');
        
        $activeLesson = $session->get('activeLesson');
        if(!$activeLesson){
            throw $this->createNotFoundException('Unable to find the active Lesson entity.');
        }
        
        $lessonPlanId = $activeLesson['lessonPlanId'];
        $lessonId = $activeLesson['lessonId'];
        
        $em = $this->getDoctrine()->getManager();
        
        $lessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($lessonPlanId);
        if(!$lessonPlan){
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }
        
        $lesson = $em->getRepository('YawmanTrainingBundle:Lesson')->find($lessonId);
        if(!$lesson){
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }
        
        $userLessonPlan = $em->getRepository('YawmanTrainingBundle:UserLessonPlan')->findOneBy(array('lessonPlan' => $lessonPlan, 'user' => $this->getUser()));
        if(!$userLessonPlan){
            throw new AccessDeniedException();
        }
        
        $lessonPlanLesson = $em->getRepository('YawmanTrainingBundle:LessonPlanLesson')->findOneBy(array('lessonPlan' => $lessonPlan, 'lesson' => $lesson));
        if(!$lessonPlanLesson){
            throw $this->createNotFoundException('Unable to find LessonPlanLesson entity.');
        }
        
        /**
         * Don't penelize a User for retaking a passed Lesson
         */
        if($userLessonPlan->getPosition() <= $lessonPlanLesson->getPosition()){
            $userLessonPlan->setPosition($lessonPlanLesson->getPosition());
            $em->persist($userLessonPlan);
        }
        
        /**
         * Create or Update the UserLesson.status
         */
        $userLesson = $em->getRepository('YawmanTrainingBundle:UserLesson')->findOneBy(array('user' => $this->getUser(), 'lesson' => $lesson));
        if(!$userLesson){
            $userLesson = new UserLesson();
            $userLesson->setUser($this->getUser());
            $userLesson->setLesson($lesson);
        }
        $userLesson->setStatus(UserLesson::PASS);
        
        $em->persist($userLesson);
        
        $em->flush();
        
        $session->getFlashBag()->add('success', 'Congratulations, you passed the ' . $lesson->getName() . ' lesson!');
        
        $session->remove('activeLesson');
        
        return $this->redirect($this->generateUrl('dashboard'));
    }
    
    /**
     * Fails a Users Lesson
     * 
     * @Secure(roles="ROLE_USER")
     * @Route("/fail", name="lesson_fail")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws AccessDeniedException
     */
    public function failAction(){
        $session = $this->get('session');
        
        $activeLesson = $session->get('activeLesson');
        if(!$activeLesson){
            throw $this->createNotFoundException('Unable to find the Active Lesson entity.');
        }
        
        $lessonPlanId = $activeLesson['lessonPlanId'];
        $lessonId = $activeLesson['lessonId'];
        
        $em = $this->getDoctrine()->getManager();
        
        $lessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($lessonPlanId);
        if(!$lessonPlan){
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }
        
        $lesson = $em->getRepository('YawmanTrainingBundle:Lesson')->find($lessonId);
        if(!$lesson){
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }
        
        $userLessonPlan = $em->getRepository('YawmanTrainingBundle:UserLessonPlan')->findOneBy(array('lessonPlan' => $lessonPlan, 'user' => $this->getUser()));
        if(!$userLessonPlan){
            throw new AccessDeniedException();
        }
        
        $lessonPlanLesson = $em->getRepository('YawmanTrainingBundle:LessonPlanLesson')->findOneBy(array('lessonPlan' => $lessonPlan, 'lesson' => $lesson));
        if(!$lessonPlanLesson){
            throw $this->createNotFoundException('Unable to find LessonPlanLesson entity.');
        }
        
        /**
         * Create or Update the UserLesson.status
         */
        $userLesson = $em->getRepository('YawmanTrainingBundle:UserLesson')->findOneBy(array('user' => $this->getUser(), 'lesson' => $lesson));
        if(!$userLesson){
            $userLesson = new UserLesson();
            $userLesson->setUser($this->getUser());
            $userLesson->setLesson($lesson);
        }
        $userLesson->setStatus(UserLesson::FAIL);
        
        $em->persist($userLesson);
        
        $em->flush();
        
        $session->getFlashBag()->add('error', 'Oh no! You didn\'t successfully complete the ' . $lesson->getName() . ' lesson!');
        
        $session->remove('activeLesson');
        
        return $this->redirect($this->generateUrl('dashboard'));
    }
    
    /**
     * Creates a form used to delete a Lesson entity
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
