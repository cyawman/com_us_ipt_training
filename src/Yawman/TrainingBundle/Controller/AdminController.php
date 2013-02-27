<?php

namespace Yawman\TrainingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Yawman\TrainingBundle\Entity\Company;
use Yawman\TrainingBundle\Form\CompanyType;
use Yawman\TrainingBundle\Entity\User;
use Yawman\TrainingBundle\Form\UserType;
use Yawman\TrainingBundle\Entity\Group;
use Yawman\TrainingBundle\Form\GroupType;
use Yawman\TrainingBundle\Entity\Lesson;
use Yawman\TrainingBundle\Form\LessonType;
use Yawman\TrainingBundle\Entity\LessonPlan;
use Yawman\TrainingBundle\Form\LessonPlanType;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('YawmanTrainingBundle:Admin:index.html.twig');
    }
    
    /**
     * Lists all Company entities.
     *
     */
    public function companyListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('YawmanTrainingBundle:Company')->findAll();

        return $this->render('YawmanTrainingBundle:Admin:company-list.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Company entity.
     *
     */
    public function companyShowAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $deleteForm = $this->companyCreateDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Admin:company-show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Company entity.
     *
     */
    public function companyNewAction()
    {
        $entity = new Company();
        $form   = $this->createForm(new CompanyType(), $entity);

        return $this->render('YawmanTrainingBundle:Admin:company-new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Company entity.
     *
     */
    public function companyCreateAction(Request $request)
    {
        $entity  = new Company();
        $form = $this->createForm(new CompanyType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_company_show', array('id' => $entity->getId())));
        }

        return $this->render('YawmanTrainingBundle:Admin:company-new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     */
    public function companyEditAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $editForm = $this->createForm(new CompanyType(), $entity);
        $deleteForm = $this->companyCreateDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Admin:company-edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Company entity.
     *
     */
    public function companyUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $deleteForm = $this->companyCreateDeleteForm($id);
        $editForm = $this->createForm(new CompanyType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_company_edit', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:Admin:company-edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Company entity.
     *
     */
    public function companyDeleteAction(Request $request, $id)
    {
        $form = $this->companyCreateDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('YawmanTrainingBundle:Company')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Company entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('company'));
    }

    private function companyCreateDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Lists all User entities.
     *
     */
    public function userListAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('YawmanTrainingBundle:User')->findAll();

        return $this->render('YawmanTrainingBundle:Admin:user-list.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function userShowAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->userCreateDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Admin:user-show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function userNewAction() {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);

        return $this->render('YawmanTrainingBundle:Admin:user-new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new User entity.
     *
     */
    public function userCreateAction(Request $request) {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_show', array('id' => $entity->getId())));
        }

        return $this->render('YawmanTrainingBundle:Admin:user-new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function userEditAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->userCreateDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Admin:user-edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing User entity.
     *
     */
    public function userUpdateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->userCreateDeleteForm($id);
        $editForm = $this->createForm(new UserType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_edit', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:Admin:user-edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     */
    public function userDeleteAction(Request $request, $id) {
        $form = $this->userCreateDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('YawmanTrainingBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_user'));
    }

    private function userCreateDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }
    
    /**
     * Lists all Group entities.
     *
     */
    public function groupListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('YawmanTrainingBundle:Group')->findAll();

        return $this->render('YawmanTrainingBundle:Admin:group-list.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Group entity.
     *
     */
    public function groupShowAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Group')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }

        $deleteForm = $this->groupCreateDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Admin:group-show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Group entity.
     *
     */
    public function groupNewAction()
    {
        $entity = new Group();
        $form   = $this->createForm(new GroupType(), $entity);

        return $this->render('YawmanTrainingBundle:Admin:group-new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Group entity.
     *
     */
    public function groupCreateAction(Request $request)
    {
        $entity  = new Group();
        $form = $this->createForm(new GroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_group_show', array('id' => $entity->getId())));
        }

        return $this->render('YawmanTrainingBundle:Admin:group-new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Group entity.
     *
     */
    public function groupEditAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Group')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }

        $editForm = $this->createForm(new GroupType(), $entity);
        $deleteForm = $this->groupCreateDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Admin:group-edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Group entity.
     *
     */
    public function groupUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Group')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Group entity.');
        }

        $deleteForm = $this->groupCreateDeleteForm($id);
        $editForm = $this->createForm(new GroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_group_edit', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:Admin:group-edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Group entity.
     *
     */
    public function groupDeleteAction(Request $request, $id)
    {
        $form = $this->groupCreateDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('YawmanTrainingBundle:Group')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Group entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_group'));
    }

    private function groupCreateDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Lesson entities.
     *
     */
    public function lessonListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('YawmanTrainingBundle:Lesson')->findAll();

        return $this->render('YawmanTrainingBundle:Admin:lesson-list.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Lesson entity.
     *
     */
    public function lessonShowAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Lesson')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }

        $deleteForm = $this->lessonCreateDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Admin:lesson-show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Lesson entity.
     *
     */
    public function lessonNewAction()
    {
        $entity = new Lesson();
        $form   = $this->createForm(new LessonType(), $entity);

        return $this->render('YawmanTrainingBundle:Admin:lesson-new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Lesson entity.
     *
     */
    public function lessonCreateAction(Request $request)
    {
        $entity  = new Lesson();
        $form = $this->createForm(new LessonType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_lesson_show', array('id' => $entity->getId())));
        }

        return $this->render('YawmanTrainingBundle:Admin:lesson-new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Lesson entity.
     *
     */
    public function lessonEditAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Lesson')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }

        $editForm = $this->createForm(new LessonType(), $entity);
        $deleteForm = $this->lessonCreateDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Admin:lesson-edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Lesson entity.
     *
     */
    public function lessonUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Lesson')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lesson entity.');
        }

        $deleteForm = $this->lessonCreateDeleteForm($id);
        $editForm = $this->createForm(new LessonType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_lesson_edit', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:Admin:lesson-edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Lesson entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->lessonCreateDeleteForm($id);
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

        return $this->redirect($this->generateUrl('admin_lesson'));
    }

    private function lessonCreateDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Lists all LessonPlan entities.
     *
     */
    public function lessonPlanListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('YawmanTrainingBundle:LessonPlan')->findAll();

        return $this->render('YawmanTrainingBundle:Admin:lesson-plan-list.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a LessonPlan entity.
     *
     */
    public function lessonPlanShowAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $deleteForm = $this->lessonPlanCreateDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Admin:lesson-plan-show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new LessonPlan entity.
     *
     */
    public function lessonPlanNewAction()
    {
        $entity = new LessonPlan();
        $form   = $this->createForm(new LessonPlanType(), $entity);

        return $this->render('YawmanTrainingBundle:Admin:lesson-plan-new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new LessonPlan entity.
     *
     */
    public function lessonPlanCreateAction(Request $request)
    {
        $entity  = new LessonPlan();
        $form = $this->createForm(new LessonPlanType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_lessonplan_show', array('id' => $entity->getId())));
        }

        return $this->render('YawmanTrainingBundle:Admin:lesson-plan-new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing LessonPlan entity.
     *
     */
    public function lessonPlanEditAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $editForm = $this->createForm(new LessonPlanType(), $entity);
        $deleteForm = $this->lessonPlanCreateDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Admin:lesson-plan-edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing LessonPlan entity.
     *
     */
    public function lessonPlanUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LessonPlan entity.');
        }

        $deleteForm = $this->lessonPlanCreateDeleteForm($id);
        $editForm = $this->createForm(new LessonPlanType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_lessonplan_edit', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:Admin:lesson-plan-edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a LessonPlan entity.
     *
     */
    public function lessonPlanDeleteAction(Request $request, $id)
    {
        $form = $this->lessonPlanCreateDeleteForm($id);
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

        return $this->redirect($this->generateUrl('admin_lessonplan'));
    }

    private function lessonPlanCreateDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
