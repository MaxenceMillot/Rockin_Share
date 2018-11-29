<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\EditPasswordType;
use App\Form\EditUtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends Controller
{
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
    public function userUpdate(EntityManagerInterface $em, Request $request)
    {
        $user = $this->getUser();

        $formUser= $this->createForm(EditUtilisateurType::class, $user);
        $formUser->handleRequest($request);

        if($formUser->isSubmitted() && $formUser->isValid()){
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Your account has been updated");

            return $this->redirectToRoute('account');

        }

        return $this->render('utilisateur/update.html.twig',
            [
                'form' => $formUser->createView(),
                'user' => $user
            ]
        );
    }

    /**
     * @Route("/account/update/password", name="account_update_password")
     */
    public function passwordUpdate(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        $formUser= $this->createForm(EditPasswordType::class, $user);
        $formUser->handleRequest($request);

        if($formUser->isSubmitted() && $formUser->isValid()){

            // Crypter le MDP
            $pass = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($pass);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Your Password has been updated");

            return $this->redirectToRoute('account');

        }

        return $this->render('utilisateur/editPassword.html.twig',
            [
                'form' => $formUser->createView(),
                'user' => $user
            ]
        );
    }

    /**
     * @Route("/account/delete/", name="account_delete")
     */
    public function deleteAccount(EntityManagerInterface $em)
    {
        $user = $this->getUser();

        $em->remove($user);
        $em->flush();

        $this->get('security.token_storage')->setToken(null);

        $this->addFlash('success', "Account successfully deleted");
        return $this->redirectToRoute('home');
    }


    ######### MY MEDIAS ############
    /**
     * @Route("/account/mymedia/list", name="myMedia_list")
     */
    public function listMyMedia(EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $id =  $user->getId();

        $repo = $em->getRepository(Media::class);

        $listeMedias = $repo->findByUtilisateur($id);

        $arrayIsPicture = [];

        foreach ($listeMedias as $media){
            $isPicture = false;
            if (file_exists('files/pictures/'.$media->getPicture().'.jpg') )
            {
                $isPicture = true;
            }
            array_push($arrayIsPicture,$isPicture);
        }

        return $this->render("media/liste.html.twig",[
            'listeMedias' => $listeMedias,
            'arrayIsPicture' => $arrayIsPicture
        ]);
    }
}
