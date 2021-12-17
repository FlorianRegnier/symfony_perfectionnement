<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class AdminProductController extends AbstractController
{

  
    /**
     * @Route("/admin/products/", name="admin_product_list")
     */
    public function listProduct(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll(); 
        return $this->render('admin/products.html.twig', ['products' => $products]);
    }


    /**
     * @Route("admin/product/{id}", name="admin_product_show")
     */
    public function showProduct($id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id); 
        
        return $this->render('admin/product.html.twig', ['product' => $product]);
    }






    /**
     * @Route("admin/update/product/{id}", name="admin_product_update")
    */                                       
    public function productUpdate($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface, Request $request )
    {
       $product = $productRepository->find($id);

       
       $productForm = $this->createForm(ProductType::class, $product); // a changer

       // Utilisation de handleRequest pour demander au formulaire de traiter les infos
       // rentrées dans le formulaire
       // Utilisation de request pour récupérer les informations rentrées dans le fromulaire
       $productForm->handleRequest($request);

       if($productForm->isSubmitted() && $productForm->isValid()){
           $entityManagerInterface->persist($product);
           $entityManagerInterface->flush();

           return $this->redirectToRoute('admin_product_list');
       }

       // redirige vers la page où le formulaire est affiché.
       return $this->render('admin/productupdate.html.twig', ['productForm' => $productForm->createView()]);
    }





   /**
    * @Route("admin/add/product/", name="admin_product_add")
   */
   public function addProduct(EntityManagerInterface $entityManagerInterface, Request $request)
   {
       $product = new Product();      

       // Création du formulaire
       $productForm = $this->createForm(ProductType::class, $product); 

       // Utilisation de handleRequest pour demander au formulaire de traiter les infos
       // rentrées dans le formulaire
       // Utilisation de request pour récupérer les informations rentrées dans le fromulaire
       $productForm->handleRequest($request);

       if($productForm->isSubmitted() && $productForm->isValid())
       {
           $entityManagerInterface->persist($product);    // pré-enregistre dans la base de données
           $entityManagerInterface->flush();           // Enregistre dans la pase de données.

           return $this->redirectToRoute('admin_product_list');
       }

       // redirige vers la page où le formulaire est affiché.
       return $this->render('admin/productupdate.html.twig', ['productForm' => $productForm->createView()]);
   }




   

   /**
    * @Route("admin/delete/product/{id}", name="admin_product_delete")
   */
   public function deleteProduct($id, productRepository $productRepository, EntityManagerInterface $entityManagerInterface)
   {
       $product = $productRepository->find($id);
       $entityManagerInterface->remove($product); // fonction remove supprime le product sélectionné
       $entityManagerInterface->flush();

       $this->addFlash(
        'notice',
        'Votre produit a été supprimé'
        );
    
       return $this->redirectToRoute("admin_product_list");
   }




}