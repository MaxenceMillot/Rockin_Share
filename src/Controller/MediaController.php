<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Media;
use App\Form\MediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $formMedia = $this->createForm(MediaType::class,$media);
        $media->setUtilisateur($this->getUser());
        $media->setDateCreated( new \DateTime());
        $media->setExtension("");
        $formMedia->handleRequest($request);
        if($formMedia->isSubmitted() && $formMedia->isValid())
        {
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
        return $this->render('media/form.html.twig',['form' => $formMedia->createView()]);
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
    public function listMyMedia(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Media::class);

        $listeMedias = $repo->findAll();

        return $this->render("media/liste.html.twig",['listeMedias' => $listeMedias]);
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

    /**
     * @return string
     */
    private function generateUniquePictureName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * Returns a JSON string with the TypeMedia of the Genre with the providen id.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listGenreOfTypeMediaAction(Request $request)
    {
        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $genreRepository = $em->getRepository(Genre::class);

        // Search the genres that belongs to the typeMedia with the given id as GET parameter "typeMediaId"
        $genres = $genreRepository->createQueryBuilder("g")
            ->where("g.typeMedia_id = :typeMediaId")
            ->setParameter("typeMediaId", $request->query->get("typeMediaId"))
            ->getQuery()
            ->getResult();

        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
        foreach($genres as $genre){
            $responseArray[] = array(
                "id" => $genre->getId(),
                "name" => $genre->getName()
            );
        }

        // Return array with structure of the neighborhoods of the providen city id
        return new JsonResponse($responseArray);

        // e.g
        // [{"id":"3","name":"Treasure Island"},{"id":"4","name":"Presidio of San Francisco"}]
    }
}
