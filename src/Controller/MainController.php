<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Utilisateur;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('main/home.html.twig');
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em){
        $user = new Utilisateur();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // Crypter le MDP
            $pass = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($pass);
            $user->setRoles(['ROLE_USER']);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Compte créé avec succès');
            return $this->redirectToRoute('home');
        }

        return $this->render('main/register.html.twig', ['form'=>$form->createView()]);

    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils){
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('main/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){
        // controller can be blank: it will never be executed!
    }


    ################ ACCOUNT ################
    /**
     * @Route("/account/", name="account")
     */
    public function userDetail(EntityManagerInterface $em){
        $repo = $em->getRepository(Media::class);

        $user = $this->getUser();
        $id =  $user->getId();

        $medias = $repo->findAllByUser($id);

        return $this->render('utilisateur/detail.html.twig',
            [
                'user'=>$user,
                'medias'=>$medias
            ]);
    }

    /**
     * @Route("/account/update/", name="account_update")
     */
    public function userUpdate(EntityManagerInterface $em, Request $request, $id=0)
    {
        $user = $this->getUser();

        $formUser= $this->createForm(RegistrationType::class, $user);
        $formUser->handleRequest($request);

        if($formUser->isSubmitted() && $formUser->isValid()){
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Your account has been deleted");

            return $this->redirectToRoute('logout');

        }


        return $this->render('utilisateur/update.html.twig',
            [
                'form' => $formUser->createView()
            ]
        );
    }

    /**
     * @Route("/account/delete", name="account_delete")
     */
    public function deleteUser(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Media::class);

        $user = $this->getUser();
        $id =  $user->getId();

        $medias = $repo->findOneOrNullByUser($id);


        if(!$medias){
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', "User has been successfully deleted");

            return $this->redirectToRoute('user_list');
        }

        $this->addFlash('danger', "Error: Could not delete user because he have medias");
        return $this->redirectToRoute('user_detail', ['id' => $id], 301);
    }

}
