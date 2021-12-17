<?php

namespace App\Controller\Front;

use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\MediaRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class BrandController extends AbstractController
{


      
    /**
     * @Route("front/brands/", name="front_list_brand")
     */
    public function listBrands(BrandRepository $brandRepository)
    {
        $brands = $brandRepository->findAll(); 
        return $this->render('front/brands.html.twig', ['brands' => $brands]);
    }


    /**
     * @Route("front/brand/{id}", name="front_show_brand")
     */
    public function showBrand($id, brandRepository $brandRepository)
    {
        $brand = $brandRepository->find($id); 
        
        return $this->render('front/brand.html.twig', ['brand' => $brand]);
    }

}