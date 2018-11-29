<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\EditUtilisateurType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * @Route("/account/mymedia/detail/{id}", name="myMedia_detail")
     */
    public function detailMyMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/account/mymedia/update/{id}", name="myMedia_update")
     */
    public function updateMyMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/account/mymedia/delete/{id}", name="myMedia_delete")
     */
    public function deleteMyMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }
}
