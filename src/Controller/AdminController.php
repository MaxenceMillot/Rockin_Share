<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function adminMenu()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


############### USERS ##############
    /**
     * @Route("/admin/user/create", name="user_create")
     */
    public function createUser()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/user/list", name="user_list")
     */
    public function userList()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/user/detail/{id]", name="user_detail")
     */
    public function userDetail()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
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
    public function deleteUser()
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
