<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\FormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminMediaController extends AbstractController
{

    /** 
     * @Route("admin/create/media", name="admin_create_media")
     */
    public function createmedia(Request $request, EntityManagerInterface $entityManagerInterface, SluggerInterface $sluggerInterface)
    {

        $media = new Media();

        $mediaForm = $this->createForm(FormType::class, $media);

        $mediaForm->handleRequest($request);

        if($mediaForm->isSubmitted() && $mediaForm->isValid())
        {
            $mediaFile = $mediaForm->get('src')->getData();

            if($mediaFile)
            {
                // on cree un nom unique avec le nom original de l image pour eviter tout pb
                $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                // on utilise slugg sur le nom original d elimage pour avoir un nom valide
                $safeFileName = $sluggerInterface->slug($originalFilename);
                // on ajoute un id unique au nom de limage
                $newFilename = $safeFileName . '-'  . uniqid() . '.' . $mediaFile->guessExtension();
                
                // on deplace le fichier dans le dossier public/media
                //la destination du fichier est enregistre dans image_directory
                //qui est defini dans le fichier  config\services.yaml
                $mediaFile->move($this->getParameter('images_directory'), $newFilename);

                $media->setSrc($newFilename);
            }

            $media->setAlt($mediaForm->get('title')->getData());

            $entityManagerInterface->persist($media);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_product_list");
        }

        return $this->render('admin/mediaform.html.twig', ['mediaForm' => $mediaForm->createView()]);
    }

}