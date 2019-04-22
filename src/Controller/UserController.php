<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/register",name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setIsActive(true);
        $form=$this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        $error=$form->getErrors();

        if($form->isSubmitted() && $form->isValid()){
            // encrypt the painpassword
            $password=$passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            // handle the entities
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success','User created'
            );
            return $this->redirectToRoute('app_homepage');
        }
        //render the form
        return $this->render('user/regform.html.twig',[
            'error'=>$error,
            'form'=>$form->createView()
            ]);
    }

    /**
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     * @Route("/login",name="app_login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils){
        $error=$authUtils->getLastAuthenticationError();
        //last username
        $lastUsername=$authUtils->getLastUsername();
        return $this->render('user/login.html.twig',[
            'error'=>$error,
            'last_username'=>$lastUsername
        ]);
    }

    /**
     * @Route("/logout",name="app_logout")
     */
    public function logout(){
        $this->redirectToRoute("/");
    }

    public function editUser(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder) {

        $old_password = $user->getPassword();

        $form=$this->createForm(EditUserType::class,$user);

        //handle the request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //data capture
            $user = $form->getData();

            if ($user->getPlainPassword() != null && $user->getPlainPassword() != '') {
                $new_password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());

                if ($old_password != $new_password) {
                    $user->setPassword($new_password);
                }
            }
            // flush to DB
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user_theusers');
        }
        //render the form
        return $this->render('user/regform.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function deteleUser(User $user) {
        if ($this->getUser() != null && in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $manager = $this->getDoctrine()
                ->getManager();
            $manager->remove($user);
            $manager->flush();
        }

        return $this->redirectToRoute('user_theusers');
    }

    public function users() {
        $user = $this->getUser();

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/theusers.html.twig', [
            'users' => $users
        ]);
    }
}