<?php

namespace App\Controller\Front;

use App\Entity\Product;
use App\Repository\MediaRepository;
use App\Repository\ProductRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TypeController extends AbstractController
{
    
    /**
     * @Route("front/types/", name="front_list_type")
     */
    public function listTypes(TypeRepository $typeRepository)
    {
        $types = $typeRepository->findAll(); 
        return $this->render('front/types.html.twig', ['types' => $types]);
    }


    /**
     * @Route("front/type/{id}", name="front_show_type")
     */
    public function showType($id, TypeRepository $typeRepository)
    {
        $type = $typeRepository->find($id); 
        
        return $this->render('front/type.html.twig', ['type' => $type]);
    }




}