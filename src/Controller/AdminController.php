<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_menu")
     */
    public function adminMenu()
    {
        return $this->render('admin/menu.html.twig');
    }


############### USERS ##############
/*
    /**
     * @Route("/admin/user/create", name="user_create")

    public function createUser(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em){
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

            $this->addFlash('success', 'Account created');
            return $this->redirectToRoute('home');
        }

return $this->render('main/register.html.twig', ['form'=>$form->createView()]);

}
*/

    /**
     * @Route("/admin/user/list", name="user_list")
     */
    public function userList(EntityManagerInterface $em){
        $repo = $em->getRepository(Utilisateur::class);

        $list = $repo->findAll();

        return $this->render("utilisateur/list.html.twig", ['utilisateurs'=>$list]);
    }

    /**
     * @Route("/admin/user/detail/{id]", name="user_detail")
     */
    public function userDetail(EntityManagerInterface $em, $id=0){
        $repo = $em->getRepository(Utilisateur::class);

        $idea = $repo->find($id);
        /*
                if($idea == null){
                    throw
                }
        */
        return $this->render('utilisateur/detail.html.twig', ['idea'=>$idea]);
    }

    /**
     * @Route("/admin/user/update/{id]", name="user_update")
     */
    public function userUpdate()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id]", name="user_delete")
     */
    public function deleteUser()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    ############ MEDIAS #############

    /**
     * @Route("/admin/media/create", name="media_create")
     */
    public function createMedia()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/media/list", name="media_list")
     */
    public function mediaList()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/media/detail/{id]", name="media_detail")
     */
    public function mediaDetail()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/media/update/{id]", name="media_update")
     */
    public function mediaUpdate()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/media/delete/{id]", name="media_delete")
     */
    public function deleteMedia()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    ############ GENRES #############

    /**
     * @Route("/admin/genre/create", name="genre_create")
     */
    public function createGenre()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/genre/list", name="genre_list")
     */
    public function genreList()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/genre/update/{id]", name="genre_update")
     */
    public function genreUpdate()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/genre/delete/{id]", name="genre_delete")
     */
    public function deleteGenre()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
