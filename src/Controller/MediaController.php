<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends Controller
{
    ######### MEDIAS ############
    /**
     * @Route("/media/list", name="media_list")
     */
    public function listMedia(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Media::class);

        $listeMedias = $repo->findAll();

        return $this->render("media/liste.html.twig",['listeMedias' => $listeMedias]);
    }

    /**
     * @Route("/account/media/create", name="media_create")
     */
    public function createMedia(EntityManagerInterface $em,Request $request)
    {


        $media = new Media();
        $formMedia = $this->createForm(MediaType::class,$media);
        $media->setUtilisateur($this->getUser());
        $media->setDateCreated( new \DateTime());
        $media->setExtension("");
        $formMedia->handleRequest($request);
        if ($formMedia->isSubmitted() && $formMedia->isValid()) {
            $file = $request->files->get('uploadedFile');
            $fileExtension = $file->getClientOriginalExtension();

            $media->setExtension($fileExtension);

            $picture = array_values($request->files->get('media'))[0];

            $pictureName = $this->generateUniquePictureName();
            $media->setPicture($pictureName);

            $em->persist($media);
            $em->flush();

            // Move the file to the directory where medias are stored
            try {
                $file->move('files/medias',$media->getId().'.'.$fileExtension);

            } catch (FileException $e) {
                $this->addFlash('danger',phpinfo());
                die();
            }

            // Move the file to the directory where pictures are stored
            try {

                $picture->move('files/pictures',$pictureName.'.jpg');
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $em->flush();
            $this->addFlash('success',"The media has been created !");

            return $this->redirectToRoute('media_list');
        }

        return $this->render(
            'media/form.html.twig',
            array('form' => $formMedia->createView())
        );
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

    /**
     * @return string
     */
    private function generateUniquePictureName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

}
