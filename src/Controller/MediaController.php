<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends Controller
{
    ######### MEDIAS ############
    /**
     * @Route("/media/list", name="media_list")
     */
    public function listMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/media/create", name="media_create")
     */
    public function createMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/media/detail/{id}", name="media_detail")
     */
    public function detailMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }


    ######### MY MEDIAS ############
    /**
     * @Route("/my_media/list", name="myMedia_list")
     */
    public function listMyMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/my_media/detail/{id}", name="myMedia_detail")
     */
    public function detailMyMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/my_media/update/{id}", name="myMedia_update")
     */
    public function updateMyMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/my_media/delete/{id}", name="myMedia_update")
     */
    public function deleteMyMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }
}
