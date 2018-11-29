<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Media;
use App\Entity\TypeMedia;
use App\Entity\Utilisateur;
use App\Form\GenreType;
use App\Form\RegistrationType;
use App\Form\TypeMediaType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
     * @Route("/admin/user/detail/{id}", name="user_detail")
     */
    public function userDetail(EntityManagerInterface $em, $id=0){
        $repo = $em->getRepository(Utilisateur::class);
        $repo2 = $em->getRepository(Media::class);

        $user = $repo->find($id);
        $medias = $repo2->findAllByUser($id);

        /*
                if($idea == null){
                    throw
                }
        */
        return $this->render('utilisateur/detail.html.twig',
            [
                'user'=>$user,
                'medias'=>$medias
            ]);
    }

    /**
     * @Route("/admin/user/update/{id}", name="user_update")
     */
    public function userUpdate(EntityManagerInterface $em, Request $request, $id=0)
    {
        $repo = $em->getRepository(Utilisateur::class);

        $user = $repo->find($id);

        $formUser= $this->createForm(RegistrationType::class, $user);
        $formUser->handleRequest($request);

        if($formUser->isSubmitted() && $formUser->isValid()){
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "User has been successfully updated");

            return $this->redirectToRoute('user_detail', ['id' => $id], 301);

        }


        return $this->render('utilisateur/update.html.twig',
            [
                'form' => $formUser->createView()
            ]
        );
    }

    /**
     * @Route("/admin/user/delete/{id}", name="user_delete")
     */
    public function deleteUser(EntityManagerInterface $em, $id=0)
    {
        $repo = $em->getRepository(Utilisateur::class);
        $repo2 = $em->getRepository(Media::class);

        $user = $repo->find($id);
        $medias = $repo2->findOneOrNullByUser($id);


        if(!$medias){
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', "User has been successfully deleted");

            return $this->redirectToRoute('user_list');
        }

        $this->addFlash('danger', "Error: Could not delete user because he have medias");
        return $this->redirectToRoute('user_detail', ['id' => $id], 301);
    }


    ############ GENRES #############
    /**
     * @Route("/admin/genre/create/", name="genre_create")
     */
    public function createGenre(EntityManagerInterface $em, Request $request){

        $genre = new Genre();

        $formGenre = $this->createForm(GenreType::class, $genre);
        $formGenre->handleRequest($request);

        if($formGenre->isSubmitted() && $formGenre->isValid()){
            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', 'new Genre successfully created');

            return $this->redirectToRoute('genre_list');
        }


        return $this->render('genre/create.html.twig',
            [
                'form' => $formGenre->createView()
            ]);
    }

    /**
     * @Route("/admin/genre/list/{id}", name="genre_list")
     */
    public function genreList(EntityManagerInterface $em, $id=null)
    {
        $repo_genre = $em->getRepository(Genre::class);
        $repo_type = $em->getRepository(TypeMedia::class);

        $genres = $repo_genre->findAllOrder();
        $types = $repo_type->findAll();

        return $this->render("genre/list.html.twig",
            [
                "genres"=>$genres,
                "types"=>$types,
                "idTypeMedia"=>$id
            ]);
    }

    /**
     * @Route("/admin/genre/list/filter/", name="genre_list_filter")
     */
    public function genreListFilter(Request $request)
    {
        $id = $request->request->get('filter');
        if($id == 0){

            return $this->redirectToRoute('genre_list');
        }
        return $this->redirectToRoute('genre_list', ['id' => $id], 301);
    }

    /**
     * @Route("/admin/genre/update/{id}", name="genre_update")
     */
    public function genreUpdate(EntityManagerInterface $em, Request $request, $id=0)
    {
        $repo = $em->getRepository(Genre::class);

        $genre = $repo->find($id);

        $formGenre = $this->createForm(GenreType::class, $genre);
        $formGenre->handleRequest($request);

        if($formGenre->isSubmitted() && $formGenre->isValid()){
            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', "Genre has been successfully updated");

            return $this->redirectToRoute('genre_list');

        }


        return $this->render('genre/update.html.twig',
            [
                'form' => $formGenre->createView()
            ]
        );
    }

    /**
     * @Route("/admin/genre/delete/{id}", name="genre_delete")
     */
    public function deleteGenre(EntityManagerInterface $em, $id=0)
    {
        $repo = $em->getRepository(Genre::class);
        $repo2 = $em->getRepository(Media::class);

        $genre = $repo->find($id);
        $medias = $repo2->findOneOrNullByGenre($id);

        if(!$medias){
            $em->remove($genre);
            $em->flush();

            $this->addFlash('success', "Genre has been successfully deleted");

            return $this->redirectToRoute('genre_list');
        }

        $this->addFlash('danger', "Error: this Genre is used by some medias");
        return $this->redirectToRoute('genre_list');

    }


    ############ TYPES MEDIAS #############
    /**
     * @Route("/admin/type/create/", name="type_create")
     */
    public function createType(EntityManagerInterface $em, Request $request){

        $type = new TypeMedia();

        $formType = $this->createForm(TypeMediaType::class, $type);
        $formType->handleRequest($request);

        if($formType->isSubmitted() && $formType->isValid()){
            $em->persist($type);
            $em->flush();

            $this->addFlash('success', 'new Type successfully created');

            return $this->redirectToRoute('type_list');
        }


        return $this->render('type/create.html.twig',
            [
                'form' => $formType->createView()
            ]);
    }

    /**
     * @Route("/admin/type/list/", name="type_list")
     */
    public function typeList(EntityManagerInterface $em)
    {
        $repo_type = $em->getRepository(TypeMedia::class);

        $types = $repo_type->findAll();

        return $this->render("type/list.html.twig",
            [
                "types"=>$types,
            ]);
    }

    /**
     * @Route("/admin/type/update/{id}", name="type_update")
     */
    public function typeUpdate(EntityManagerInterface $em, Request $request, $id=0)
    {
        $repo = $em->getRepository(TypeMedia::class);

        $type = $repo->find($id);

        $formType = $this->createForm(TypeMediaType::class, $type);
        $formType->handleRequest($request);

        if($formType->isSubmitted() && $formType->isValid()){
            $em->persist($type);
            $em->flush();

            $this->addFlash('success', "media Type has been successfully updated");

            return $this->redirectToRoute('type_list');

        }


        return $this->render('type/update.html.twig',
            [
                'form' => $formType->createView()
            ]
        );
    }

    /**
     * @Route("/admin/type/delete/{id}", name="type_delete")
     */
    public function deleteType(EntityManagerInterface $em, $id=0)
    {
        $repo = $em->getRepository(TypeMedia::class);
        $repo2 = $em->getRepository(Genre::class);

        $type = $repo->find($id);
        $genre = $repo2->findOneOrNullByTypeMedia($id);

        if(!$genre){
            $em->remove($type);
            $em->flush();

            $this->addFlash('success', "Type has been successfully deleted");

            return $this->redirectToRoute('type_list');
        }

        $this->addFlash('danger', "Error: this Type is used by a Genre");
        return $this->redirectToRoute('type_list');

    }
}
