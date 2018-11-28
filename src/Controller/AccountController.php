<?php

namespace App\Controller;

use App\Entity\Media;
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
}
