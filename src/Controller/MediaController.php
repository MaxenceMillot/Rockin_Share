<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Form\MediaUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
     * @Route("/media/create/", name="media_create")
     */
    public function createMedia(EntityManagerInterface $em, AuthorizationCheckerInterface $authChecker, Request $request)
    {
        if (!$authChecker->isGranted('ROLE_USER')) {

            $this->addFlash('danger', "Please, login to your account to process");
            return $this->redirectToRoute('login');
        }

        $media = new Media();
        $formMedia = $this->createForm(MediaType::class,$media);
        $media->setUtilisateur($this->getUser());
        $media->setDateCreated( new \DateTime());
        $media->setExtension("");
        $formMedia->handleRequest($request);
        if ($formMedia->isSubmitted() && $formMedia->isValid()) {

            $file = $request->files->get('uploadedFile');
            if ($file != null) {
                $fileExtension = $file->getClientOriginalExtension();

                $media->setExtension($fileExtension);

                $picture = array_values($request->files->get('media'))[0];

                $pictureName = $this->generateUniquePictureName();
                $media->setPicture($pictureName);

                $em->persist($media);
                $em->flush();

                // Move the file to the directory where medias are stored
                try {
                    $file->move('files/medias', $media->getId() . '.' . $fileExtension);

                } catch (FileException $e) {
                    $this->addFlash('danger', phpinfo());
                    die();
                }

                if($picture != null) {
                    // Move the file to the directory where pictures are stored
                    try {

                        $picture->move('files/pictures', $pictureName . '.jpg');
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                }
                $em->flush();
                $this->addFlash('success', "The media has been created !");

                return $this->redirectToRoute('media_list');
            }
            else
            {
                $this->addFlash('danger', "tu m'prend pour un con ?");
                $this->redirectToRoute('media_create');
            }
        }

        return $this->render(
            'media/form.html.twig',
            array('form' => $formMedia->createView(),
                  'edit' => false)
        );
    }

    /**
     * @Route("/media/update/{id}", name="media_update")
     */
    public function updateMedia(EntityManagerInterface $em,Request $request, AuthorizationCheckerInterface $authChecker,$id=0)
    {
        if (!$authChecker->isGranted('ROLE_USER')) {
            $this->addFlash('error', "Please, login to your account to process");
            return $this->redirectToRoute('login');
        }

        $repo = $em->getRepository(Media::class);
        $media = $repo->find($id);

        $formMedia = $this->createForm(MediaUpdateType::class,$media);
        $media->setUtilisateur($this->getUser());
        $media->setDateCreated( new \DateTime());
        $media->setExtension("");

        $formMedia->handleRequest($request);
        if ($formMedia->isSubmitted() && $formMedia->isValid()) {

            $file = $request->files->get('uploadedFile');
            if ($file != null) {
                $fileExtension = $file->getClientOriginalExtension();

                $media->setExtension($fileExtension);

                $picture = array_values($request->files->get('media_update'))[0];

                $pictureName = $this->generateUniquePictureName();
                $media->setPicture($pictureName);

                $em->persist($media);
                $em->flush();

                // Move the file to the directory where medias are stored
                try {
                    $file->move('files/medias', $media->getId() . '.' . $fileExtension);

                } catch (FileException $e) {
                    $this->addFlash('danger', phpinfo());
                    die();
                }

                if($picture != null) {
                    // Move the file to the directory where pictures are stored
                    try {

                        $picture->move('files/pictures', $pictureName . '.jpg');
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                }
                $em->flush();
                $this->addFlash('success', "The media has been updated !");

                return $this->redirectToRoute('media_list');
            }
            else
            {
                $this->addFlash('danger', "Vous devez remplir les champs nécessaires à la modification d'un média");
                $this->redirectToRoute('media_create');
            }
        }

        return $this->render(
            'media/form.html.twig',
            array('form' => $formMedia->createView(),
                  'edit' => true)
        );
    }

    /**
     * @Route("/media/detail/{id}", name="media_detail")
     */
    public function detailMedia(EntityManagerInterface $em, $id = 0)
    {
        $repo = $em->getRepository(Media::class);
        $media = $repo->find($id);
        $genres = $media->getGenre();
        $genre = $genres[0];

        $isPicture = true;
        if (!file_exists($media->getPicture().'.jpg') )
        {
            $isPicture = false;
        }

        return $this->render('media/detail.html.twig',
            [
                'media'=>$media,
                'typeMedia' => $genre->gettypeMedia()->getName(),
                'isPicture' => $isPicture
            ]);
    }

    /**
     * @Route("/media/delete/{idUser}/{idMedia}", name="media_delete")
     */
    public function deleteMedia(EntityManagerInterface $em, AuthorizationCheckerInterface $authChecker, $idUser=0, $idMedia=0)
    {
        if (!$authChecker->isGranted('ROLE_USER')) {
            $this->addFlash('error', "Please, login to your account to process");
            return $this->redirectToRoute('login');
        }

        $repo = $em->getRepository(Media::class);

        $media = $repo->find($idMedia);

        // Check if is admin or if media belong to user
        if ($authChecker->isGranted('ROLE_ADMIN') === true) {
            //is ok
        }elseif($this->getUser()->getId() == $media->getUtilisateur()->getId()){
            //is ok
        }else{
            $this->addFlash('danger', "Sorry, you cannot delete other users medias");
            return $this->redirectToRoute('media_list');
        }

        $em->remove($media);
        $em->flush();

        $this->addFlash('success', "Media successfully deleted");

        // Redirect to user details
        if ($authChecker->isGranted('ROLE_ADMIN') === true) {
            if($idUser == "0"){
                return $this->redirectToRoute('media_list');
            }

            return $this->redirectToRoute('user_detail', ['id' => $idUser], 301);
        }

        return $this->redirectToRoute('account');

    }

    /**
     * @Route("/media/update/{id}", name="media_update")
     */
    public function mediaUpdate(EntityManagerInterface $em, Request $request, $id=0)
    {

        $repo = $em->getRepository(Media::class);

        $media = $repo->find($id);

        $formMedia = $this->createForm(MediaType::class, $media);
        $formMedia->handleRequest($request);

        if($formMedia->isSubmitted() && $formMedia->isValid()){
            $em->persist($media);
            $em->flush();

            $this->addFlash('success', "Media has been successfully updated");

            return $this->redirectToRoute('media_list');

        }


        return $this->render('media/form.html.twig',
            [
                'form' => $formMedia->createView()
            ]
        );
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
