<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
    public function createMedia(EntityManagerInterface $em,Request $request)
    {
        $media = new Media();
        $formIdea = $this->createForm(MediaType::class,$media);
        $media->setAuthor($this->getUser()->getUsername());

        $formIdea->handleRequest($request);
        if($formIdea->isSubmitted() && $formIdea->isValid())
        {
            $idea->setDateCreated( new \DateTime());
            $em->persist($idea);
            $em->flush();

            $this->addFlash('success',"L'idée a été ajoutée");

            return $this->redirectToRoute('idea_list');
        }
        return $this->render('idea/formIdea.html.twig',['formIdea' => $formIdea->createView()]);
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
     * @Route("/user/media/list", name="myMedia_list")
     */
    public function listMyMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/user/media/detail/{id}", name="myMedia_detail")
     */
    public function detailMyMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/user/media/update/{id}", name="myMedia_update")
     */
    public function updateMyMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/user/media/delete/{id}", name="myMedia_delete")
     */
    public function deleteMyMedia()
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }
}
