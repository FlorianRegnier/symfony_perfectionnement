<?php

namespace App\Controller\Admin;


use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class AdminTypeController extends AbstractController
{

  
   
    public function listType(TypeRepository $typeRepository)
    {
        $types = $typeRepository->findAll(); 
        return $this->render('admin/types.html.twig', ['types' => $types]);
    }


    

   
    public function showType($id, TypeRepository $typeRepository)
    {
        $type = $typeRepository->find($id); 
        
        return $this->render('admin/type.html.twig', ['type' => $type]);
    }






                                     
    public function typeUpdate($id, TypeRepository $typeRepository, EntityManagerInterface $entityManagerInterface, Request $request )
    {
       $type = $typeRepository->find($id);

       
       $typeForm = $this->createForm(TypeType::class, $type); // a changer

       // Utilisation de handleRequest pour demander au formulaire de traiter les infos
       // rentrées dans le formulaire
       // Utilisation de request pour récupérer les informations rentrées dans le fromulaire
       $typeForm->handleRequest($request);

       if($typeForm->isSubmitted() && $typeForm->isValid()){
           $entityManagerInterface->persist($type);
           $entityManagerInterface->flush();

           return $this->redirectToRoute('admin_types_list');
       }

       // redirige vers la page où le formulaire est affiché.
       return $this->render('admin/typetupdate.html.twig', ['typeForm' => $typeForm->createView()]);
    }





   
   public function addType(EntityManagerInterface $entityManagerInterface, Request $request)
   {
       $type = new Type();      

       // Création du formulaire
       $typeForm = $this->createForm(TypeType::class, $type); 

       // Utilisation de handleRequest pour demander au formulaire de traiter les infos
       // rentrées dans le formulaire
       // Utilisation de request pour récupérer les informations rentrées dans le fromulaire
       $typeForm->handleRequest($request);

       if($typeForm->isSubmitted() && $typeForm->isValid())
       {
           $entityManagerInterface->persist($type);    // pré-enregistre dans la base de données
           $entityManagerInterface->flush();           // Enregistre dans la pase de données.

           return $this->redirectToRoute('admin_types_list');
       }

       // redirige vers la page où le formulaire est affiché.
       return $this->render('admin/typetupdate.html.twig', ['typeForm' => $typeForm->createView()]);
   }




   

   
   public function deleteType($id, TypeRepository $typeRepository, EntityManagerInterface $entityManagerInterface)
   {
       $type = $typeRepository->find($id);
       $entityManagerInterface->remove($type); // fonction remove supprime le product sélectionné
       $entityManagerInterface->flush();

       $this->addFlash(
        'notice',
        'Votre produit a été supprimé'
        );
    
       return $this->redirectToRoute("admin_types_list");
   }




}