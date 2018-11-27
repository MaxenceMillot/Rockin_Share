<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Utilisateur;
use App\Form\GenreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('utilisateur/update.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id]", name="user_delete")
     */
    public function deleteUser()
    {
        return $this->render('user/delete.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    ############ GENRES #############
    /**
     * @Route("/admin/genre/create", name="genre_create")
     */
    public function createGenre(EntityManagerInterface $em, Request $request){

        $genre = new Genre();

        $formGenre = $this->createForm(GenreType::class, $genre);
        $formGenre->handleRequest($request);

        dump($request);
        die();
        if($formGenre->isSubmitted() && $formGenre->isValid()){

            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', 'Genre has been successfully created');

            return $this->redirectToRoute('genre_list');
        }


        return $this->render('genre/create.html.twig',
            [
                'form' => $formGenre->createView()
            ]);
    }

    /**
     * @Route("/admin/genre/list", name="genre_list")
     */
    public function genreList(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Genre::class);

        $list = $repo->findAll();

        return $this->render("genre/list.html.twig", ["genres"=>$list]);
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


    ############ TYPES MEDIAS #############
}
