<?php

namespace Yawman\TrainingBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Yawman\TrainingBundle\Entity\Company;
use Yawman\TrainingBundle\Form\CompanyType;

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
     * @Secure(roles="ROLE_MANAGER")
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
        
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            if($this->getUser()->getCompany()->getId() !== $entity->getId()){
                throw new AccessDeniedException();
            }
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YawmanTrainingBundle:Company:show.html.twig', array('entity' => $entity, 'delete_form' => $deleteForm->createView()));
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
            if($this->getUser()->getCompany()->getId() !== $entity->getId()){
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
            if($this->getUser()->getCompany()->getId() !== $entity->getId()){
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
    public function listAvailableUsersAction($companyId){
        $em = $this->getDoctrine()->getEntityManager();
        
        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($companyId);
        if(!$company){
            throw $this->createNotFoundException('Unable to find Company entity.');
        }
        
        $users = $em->getRepository('YawmanTrainingBundle:User')->findBy(array('company' => null));
        
        $form = $this->createFormBuilder()
                        ->add('users', 'entity', array('class' => 'YawmanTrainingBundle:User', 'choices' => $users, 'property' => 'username', 'label' => ' ', 'multiple' => true))
                        ->getForm();
        
        return $this->render('YawmanTrainingBundle:Company:list-available-users.html.twig', array('form' => $form->createView(), 'company' => $company));
    }
    
    /**
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{companyId}/add-users", requirements={"_method" = "post", "companyId" = "\d+"}, name="company_add_users")
     * @param int $companyId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function addUsersAction($companyId){
        $em = $this->getDoctrine()->getEntityManager();
        
        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($companyId);
        if(!$company){
            throw $this->createNotFoundException('Unable to find Company entity.');
        }
        
        
    }
    
    
    /**
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{companyId}/remove-user/{userId}", requirements={"companyId" = "\d+", "userId" = "\d+"}, name="company_remove_user")
     * @param int $companyId
     * @param int $userId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function removeUserAction($companyId, $userId){
        $em = $this->getDoctrine()->getEntityManager();
        
        $company = $em->getRepository('YawmanTrainingBundle:Company')->find($companyId);
        if(!$company){
            throw $this->createNotFoundException('Unable to find Company entity.');
        }
        
        $user = $em->getRepository('YawmanTrainingBundle:User')->find($userId);
        if(!$user){
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        $user->setCompany(null);
        
        $em->persist($user);
        $em->flush();
        
        $this->get('session')->setFlash('success', 'The update was successful!');
        
        return $this->redirect($this->generateUrl('company_show', array("id" => $companyId)));
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
