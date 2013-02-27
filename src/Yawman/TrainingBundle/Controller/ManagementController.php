<?php

namespace Yawman\TrainingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Yawman\TrainingBundle\Form\CompanyType;
use Yawman\TrainingBundle\Entity\User;
use Yawman\TrainingBundle\Entity\UserLesson;
use Yawman\TrainingBundle\Form\UserType;
use Yawman\TrainingBundle\Model\Registration;
use Yawman\TrainingBundle\Form\RegistrationType;

class ManagementController extends Controller
{
    public function indexAction()
    {
        return $this->render('YawmanTrainingBundle:Management:index.html.twig');
    }
    
    public function companyEditAction(){
        $user = $this->getUser();
        
        $entity = $user->getCompany();
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }
        
        $editForm = $this->createForm(new CompanyType(), $entity);

        return $this->render('YawmanTrainingBundle:Management:company-edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    
    public function companyUpdateAction(Request $request)
    {
        $user = $this->getUser();
        
        $entity = $user->getCompany();
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }
        
        $editForm = $this->createForm(new CompanyType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('management_company_edit'));
        }

        return $this->render('YawmanTrainingBundle:Management:company-edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    
    public function userListAction() {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        
        $company = $user->getCompany();
        
        $entities = $em->getRepository('YawmanTrainingBundle:User')->findBy(array('company' => $company));

        return $this->render('YawmanTrainingBundle:Management:user-list.html.twig', array(
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

        return $this->render('YawmanTrainingBundle:Management:user-show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function userNewAction() {
        $form = $this->createForm(new RegistrationType(), new Registration());

        return $this->render('YawmanTrainingBundle:Management:user-new.html.twig', array('form' => $form->createView()));
    }

    /**
     * Creates a new User entity.
     *
     */
    public function userCreateAction() {
        $em = $this->getDoctrine()->getEntityManager();
        
        $form = $this->createForm(new RegistrationType(), new Registration());

        $form->bind($this->getRequest());

        try {
            if ($form->isValid()) {
                $registration = $form->getData();

                if ($registration->getPassword() != $registration->getPasswordConfirm()) {
                    $form->addError(new FormError('Password and Confirmation Password did not match'));
                    throw new \Exception("Manual form error added");
                }
                
                $entity = $em->getRepository('YawmanTrainingBundle:User')->findOneBy(array('email' => $registration->getEmail()));
                
                if($entity){
                    $form->addError(new FormError('This email address already exists in the system.'));
                    throw new \Exception("Manual form error added");
                }
                
                $manager = $this->getUser();
        
                $group = $em->getRepository('YawmanTrainingBundle:Group')->findOneBy(array('name' => 'user'));
                
                $user = new User();
                $user->setUsername($registration->getEmail());
                $user->setEmail($registration->getEmail());
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($registration->getPassword(), $user->getSalt());
                $user->setPassword($password);
                $user->setCompany($manager->getCompany());
                $user->addGroup($group);
                
                foreach($manager->getLessonplans() as $lessonPlan){
                    $user->addLessonplan($lessonPlan);
                    $em->persist($user);
                    $em->flush();
                    foreach($lessonPlan->getLessons() as $lesson){
                        $userLesson = new UserLesson();
                        $userLesson->setUser($user);
                        $userLesson->setLesson($lesson);
                        $userLesson->setStatus('incomplete');
                        $em->persist($userLesson);
                        $em->flush();
                    }
                }
                
                return $this->redirect($this->generateUrl('management_user'));
            }
        } catch (\Exception $e) {
            
        }

        return $this->render('YawmanTrainingBundle:Management:user-new.html.twig', array('form' => $form->createView()));
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

        return $this->render('YawmanTrainingBundle:Management:user-edit.html.twig', array(
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

            return $this->redirect($this->generateUrl('management_user_edit', array('id' => $id)));
        }

        return $this->render('YawmanTrainingBundle:Management:user-edit.html.twig', array(
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

        return $this->redirect($this->generateUrl('management_user'));
    }

    private function userCreateDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }
}
