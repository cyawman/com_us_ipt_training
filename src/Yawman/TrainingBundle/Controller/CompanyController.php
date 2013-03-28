<?php

namespace Yawman\TrainingBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Yawman\TrainingBundle\Entity\Company;
use Yawman\TrainingBundle\Entity\User;
use Yawman\TrainingBundle\Entity\UserLessonPlan;
use Yawman\TrainingBundle\Form\CompanyType;
use Yawman\TrainingBundle\Form\UserType;

/**
 * Controls the management of Companies
 * 
 * @author Chris Yawman
 * @Route("/company")
 */
class CompanyController extends Controller {

    /**
     * Lists the Company entities
     * 
     * @Secure(roles="ROLE_MANAGER")
     * @Route("/", name="company")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $company = $this->getUser()->getCompany();

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $entities = $em->getRepository('YawmanTrainingBundle:Company')->findBy(array('id' => $company->getId()));
        } else {
            $entities = $em->getRepository('YawmanTrainingBundle:Company')->findAll();
        }

        return $this->render('YawmanTrainingBundle:Company:index.html.twig', array('entities' => $entities));
    }

    /**
     * Displays a Company entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/show", requirements={"id" = "\d+"}, name="company_show")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Company:show.html.twig', array('entity' => $entity, 'delete_form' => $deleteForm->createView()));
    }

    /**
     * Displays Users in a Company
     * 
     * @Secure(roles="ROLE_MANAGER")
     * @Route("/{id}/show-users", requirements={"id" = "\d+"}, name="company_show_users")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showUsersAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            if ($this->getUser()->getCompany()->getId() !== $entity->getId()) {
                throw new AccessDeniedException();
            }
        }

        return $this->render('YawmanTrainingBundle:Company:show-users.html.twig', array('entity' => $entity));
    }

    /**
     * Creates a new Company entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/new", name="company_new")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction() {
        $entity = new Company();
        $form = $this->createForm(new CompanyType(), $entity);

        return $this->render('YawmanTrainingBundle:Company:new.html.twig', array('entity' => $entity, 'form' => $form->createView()));
    }

    /**
     * Creates a new Company entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/create", requirements={"_method" = "post"}, name="company_create")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request) {
        $entity = new Company();
        $form = $this->createForm(new CompanyType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('company_show', array('id' => $entity->getId())));
        }

        return $this->render('YawmanTrainingBundle:Company:new.html.twig', array('entity' => $entity, 'form' => $form->createView()));
    }

    /**
     * Creates a new Company User
     * 
     * @Secure(roles="ROLE_MANAGER")
     * @Route("/{id}/new-user", requirements={"id" = "\d+"}, name="company_new_user")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function newUserAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($id);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            if ($this->getUser()->getCompany()->getId() !== $company->getId()) {
                throw new AccessDeniedException();
            }
        }

        $entity = new User();

        $form = $this->createForm(new UserType(), $entity);
        $form->remove('company');
        $form->remove('groups');

        return $this->render('YawmanTrainingBundle:Company:new-user.html.twig', array('entity' => $entity, 'form' => $form->createView(), 'company' => $company));
    }

    /**
     * Creates a new Company entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{companyId}/create-user", requirements={"_method" = "post", "companyId" = "\d+"}, name="company_create_user")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $companyId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createUserAction(Request $request, $companyId) {
        $em = $this->getDoctrine()->getEntityManager();

        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($companyId);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            if ($this->getUser()->getCompany()->getId() !== $company->getId()) {
                throw new AccessDeniedException();
            }
        }

        $entity = new User();
        $form = $this->createForm(new UserType(), $entity);
        $form->remove('company');
        $form->remove('groups');
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $group = $em->getRepository('YawmanTrainingBundle:Group')->findOneBy(array('role' => 'ROLE_USER'));

            $entity->setUsername($data->getEmail());
            $entity->setCompany($company);
            $entity->addGroup($group);
            $em->persist($entity);
            $em->flush();
            
            foreach ($company->getLessonPlans() as $lessonPlan) {
                $userLessonPlan = $em->getRepository('YawmanTrainingBundle:UserLessonPlan')->findOneBy(array('user' => $entity, 'lessonPlan' => $lessonPlan));
                if (!$userLessonPlan) {
                    $userLessonPlan = new UserLessonPlan();
                    $userLessonPlan->setUser($entity);
                    $userLessonPlan->setLessonPlan($lessonPlan);
                    $em->persist($userLessonPlan);
                }
            }

            $em->flush();

            return $this->redirect($this->generateUrl('dashboard'));
        }

        return $this->render('YawmanTrainingBundle:Company:new-user.html.twig', array('entity' => $entity, 'form' => $form->createView(), 'company' => $company));
    }

    /**
     * Provides a form to edit a Company entity
     * 
     * @Secure(roles="ROLE_MANAGER")
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="company_edit")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            if ($this->getUser()->getCompany()->getId() !== $entity->getId()) {
                throw new AccessDeniedException();
            }
        }

        $editForm = $this->createForm(new CompanyType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Company:edit.html.twig', array('entity' => $entity, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()));
    }

    /**
     * Provides a form to update a Company entity
     * 
     * @Secure(roles="ROLE_MANAGER")
     * @Route("/{id}/update", requirements={"_method" = "post", "id" = "\d+"}, name="company_update")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YawmanTrainingBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            if ($this->getUser()->getCompany()->getId() !== $entity->getId()) {
                throw new AccessDeniedException();
            }
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CompanyType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('success', 'Your update was successful!');

            return $this->redirect($this->generateUrl('company_edit', array('id' => $id)));
        }

        $this->get('session')->setFlash('error', 'The update was unsuccessful.');

        return $this->render('YawmanTrainingBundle:Company:edit.html.twig', array('entity' => $entity, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()));
    }

    /**
     * Deletes a Company entity
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="company_delete")
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
            $entity = $em->getRepository('YawmanTrainingBundle:Company')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Company entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('company'));
    }

    /**
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{companyId}/list-available-users", requirements={"companyId" = "\d+"}, name="company_list_available_users")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function listAvailableUsersAction($companyId) {
        $em = $this->getDoctrine()->getEntityManager();

        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($companyId);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $form = $this->createAvailableUsersForm();

        return $this->render('YawmanTrainingBundle:Company:list-available-users.html.twig', array('form' => $form->createView(), 'company' => $company));
    }

    /**
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{companyId}/add-users", requirements={"_method" = "post", "companyId" = "\d+"}, name="company_add_users")
     * @param int $companyId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function addUsersAction(Request $request, $companyId) {
        $em = $this->getDoctrine()->getEntityManager();

        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($companyId);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $form = $this->createAvailableUsersForm();
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();

            foreach ($data['users'] as $user) {
                $user->setCompany($company);
                $em->persist($user);
                foreach ($company->getLessonPlans() as $lessonPlan) {
                    $userLessonPlan = $em->getRepository('YawmanTrainingBundle:UserLessonPlan')->findOneBy(array('user' => $user, 'lessonPlan' => $lessonPlan));
                    if (!$userLessonPlan) {
                        $userLessonPlan = new UserLessonPlan();
                        $userLessonPlan->setUser($user);
                        $userLessonPlan->setLessonPlan($lessonPlan);
                        $em->persist($userLessonPlan);
                    }
                }
            }

            $em->flush();

            $this->get('session')->setFlash('success', 'The update was successful!');

            return $this->redirect($this->generateUrl('company_show', array("id" => $companyId)));
        }
    }

    /**
     * @Secure(roles="ROLE_MANAGER")
     * @Route("/{companyId}/remove-user/{userId}", requirements={"companyId" = "\d+", "userId" = "\d+"}, name="company_remove_user")
     * @param int $companyId
     * @param int $userId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function removeUserAction($companyId, $userId) {
        $em = $this->getDoctrine()->getEntityManager();

        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($companyId);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $user = $em->getRepository('YawmanTrainingBundle:User')->find($userId);
        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            if ($this->getUser()->getCompany()->getId() !== $company->getId()) {
                throw new AccessDeniedException();
            }
        }

        $user->setCompany(null);
        $em->persist($user);

        foreach ($company->getLessonPlans() as $lessonPlan) {
            $userLessonPlan = $em->getRepository('YawmanTrainingBundle:UserLessonPlan')->findOneBy(array('user' => $user, 'lessonPlan' => $lessonPlan));
            if ($userLessonPlan) {
                $em->remove($userLessonPlan);
            }
        }

        $em->flush();

        $this->get('session')->setFlash('success', 'The update was successful!');

        $request = $this->getRequest();
        $redirect = $this->generateUrl('company_show', array("id" => $companyId));
        if($request->query->has('redirect')){
            $redirect = $request->query->get('redirect');
        }
        
        return $this->redirect($redirect);
    }

    /**
     * 
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{companyId}/list-available-lesson-plans", requirements={"companyId" = "\d+"}, name="company_list_available_lesson_plans")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function listAvailableLessonPlansAction($companyId) {
        $em = $this->getDoctrine()->getEntityManager();

        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($companyId);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $form = $this->createAvailableLessonPlansForm($company);

        return $this->render('YawmanTrainingBundle:Company:list-available-lesson-plans.html.twig', array('form' => $form->createView(), 'company' => $company));
    }

    /**
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{companyId}/add-lesson-plans", requirements={"_method" = "post", "companyId" = "\d+"}, name="company_add_lesson_plans")
     * @param int $companyId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function addLessonPlansAction(Request $request, $companyId) {
        $em = $this->getDoctrine()->getEntityManager();

        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($companyId);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $form = $this->createAvailableLessonPlansForm($company);
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();

            foreach ($data['lessonPlans'] as $lessonPlan) {
                $company->addLessonPlan($lessonPlan);
                $em->persist($company);

                foreach ($company->getUsers() as $user) {
                    $userLessonPlan = $em->getRepository('YawmanTrainingBundle:UserLessonPlan')->findOneBy(array('user' => $user, 'lessonPlan' => $lessonPlan));
                    if (!$userLessonPlan) {
                        $userLessonPlan = new UserLessonPlan();
                        $userLessonPlan->setUser($user);
                        $userLessonPlan->setLessonPlan($lessonPlan);
                        $em->persist($userLessonPlan);
                    }
                }
            }

            $em->flush();

            $this->get('session')->setFlash('success', 'The update was successful!');

            return $this->redirect($this->generateUrl('company_show', array("id" => $companyId)));
        }

        return $this->render('YawmanTrainingBundle:Company:list-available-lesson-plans.html.twig', array('form' => $form->createView(), 'company' => $company));
    }

    /**
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{companyId}/remove-lesson-plan/{lessonPlanId}", requirements={"companyId" = "\d+", "lessonPlanId" = "\d+"}, name="company_remove_lesson_plan")
     * @param int $companyId
     * @param int $lessonPlanId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function removeLessonPlanAction($companyId, $lessonPlanId) {
        $em = $this->getDoctrine()->getEntityManager();

        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($companyId);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $lessonPlan = $em->getRepository('YawmanTrainingBundle:LessonPlan')->find($lessonPlanId);
        if (!$lessonPlan) {
            throw $this->createNotFoundException('Unable to find Lesson Plan entity.');
        }

        $company->removeLessonPlan($lessonPlan);
        $em->persist($company);

        foreach ($company->getUsers() as $user) {
            $userLessonPlan = $em->getRepository('YawmanTrainingBundle:UserLessonPlan')->findOneBy(array('user' => $user, 'lessonPlan' => $lessonPlan));
            if ($userLessonPlan) {
                $em->remove($userLessonPlan);
            }
        }

        $em->flush();

        $this->get('session')->setFlash('success', 'The update was successful!');

        return $this->redirect($this->generateUrl('company_show', array("id" => $companyId)));
    }

    /**
     * Provides a form of available Lesson Plans for a Company
     * 
     * @param int $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\Form\FormBuilder
     */
    public function createAvailableLessonPlansForm($company) {
        $em = $this->getDoctrine()->getEntityManager();

        $lessonPlans = $em->getRepository('YawmanTrainingBundle:LessonPlan')->findLessonPlansNotWithCompany($company);

        return $this->createFormBuilder()
                        ->add('lessonPlans', 'entity', array('class' => 'YawmanTrainingBundle:LessonPlan', 'choices' => $lessonPlans, 'property' => 'name', 'label' => ' ', 'multiple' => true))
                        ->getForm();
    }

    /**
     * Provides a form of available Users for a Company
     * 
     * @return \Symfony\Component\Form\FormBuilder
     */
    public function createAvailableUsersForm() {
        $em = $this->getDoctrine()->getEntityManager();

        $users = $em->getRepository('YawmanTrainingBundle:User')->findBy(array('company' => null));

        return $this->createFormBuilder()
                        ->add('users', 'entity', array('class' => 'YawmanTrainingBundle:User', 'choices' => $users, 'property' => 'username', 'label' => ' ', 'multiple' => true))
                        ->getForm();
    }

    /**
     * Provides a form used to delete a Company entity
     * 
     * @param int $id
     * @return \Symfony\Component\Form\FormBuilder
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
