<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class AdminBrandController extends AbstractController
{

  
   
    public function listBrand(BrandRepository $brandRepository)
    {
        $brands = $brandRepository->findAll(); 
        return $this->render('admin/brands.html.twig', ['brands' => $brands]);
    }


    

   
    public function showBrand($id, BrandRepository $brandRepository)
    {
        $brand = $brandRepository->find($id); 
        
        return $this->render('admin/brand.html.twig', ['brand' => $brand]);
    }






                                     
    public function brandUpdate($id, BrandRepository $brandRepository, EntityManagerInterface $entityManagerInterface, Request $request )
    {
       $brand = $brandRepository->find($id);

       
       $brandForm = $this->createForm(BrandType::class, $brand); // a changer

       // Utilisation de handleRequest pour demander au formulaire de traiter les infos
       // rentrées dans le formulaire
       // Utilisation de request pour récupérer les informations rentrées dans le fromulaire
       $brandForm->handleRequest($request);

       if($brandForm->isSubmitted() && $brandForm->isValid()){
           $entityManagerInterface->persist($brand);
           $entityManagerInterface->flush();

           return $this->redirectToRoute('admin_brand_list');
       }

       // redirige vers la page où le formulaire est affiché.
       return $this->render('admin/brandupdate.html.twig', ['brandForm' => $brandForm->createView()]);
    }





   
   public function addBrand(EntityManagerInterface $entityManagerInterface, Request $request)
   {
       $brand = new Brand();      

       // Création du formulaire
       $brandForm = $this->createForm(BrandType::class, $brand); 

       // Utilisation de handleRequest pour demander au formulaire de traiter les infos
       // rentrées dans le formulaire
       // Utilisation de request pour récupérer les informations rentrées dans le fromulaire
       $brandForm->handleRequest($request);

       if($brandForm->isSubmitted() && $brandForm->isValid())
       {
           $entityManagerInterface->persist($brand);    // pré-enregistre dans la base de données
           $entityManagerInterface->flush();           // Enregistre dans la pase de données.

           return $this->redirectToRoute('admin_brand_list');
       }

       // redirige vers la page où le formulaire est affiché.
       return $this->render('admin/brandupdate.html.twig', ['brandForm' => $brandForm->createView()]);
   }




   

   
   public function deleteBrand($id, BrandRepository $brandRepository, EntityManagerInterface $entityManagerInterface)
   {
       $brand = $brandRepository->find($id);
       $entityManagerInterface->remove($brand); // fonction remove supprime le product sélectionné
       $entityManagerInterface->flush();

       $this->addFlash(
        'notice',
        'Votre marque a été supprimée'
        );
    
       return $this->redirectToRoute("admin_brand_list");
   }




}