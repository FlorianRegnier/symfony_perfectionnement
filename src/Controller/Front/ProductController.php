<?php

namespace App\Controller\Front;

use App\Entity\Like;
use App\Entity\Product;
use App\Repository\LikeRepository;
use App\Repository\MediaRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;






class ProductController extends AbstractController
{

    // creer les 2 fct qui affichent la liste des produits avec leur name
    // prix et le name de leur type et le name d eleur brand

    //affiche grace a lid un produit en particulier et toutes les info


  



    /**
     * @Route("front/products/", name="front_list_product")
     */
    public function listProduct(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll(); 
        return $this->render('front/products.html.twig', ['products' => $products]);
    }


    /**
     * @Route("front/product/{id}", name="front_show_product")
     */
    public function showProduct($id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id); 
        
        return $this->render('front/product.html.twig', ['product' => $product]);
    }


    /**
     * @Route("front/search/", name="front_search")
     */
    public function frontSearch(ProductRepository $productRepository, Request $request)
    {
        //recuperer els donnes du tableau
        $term = $request->query->get('term');// query car le form est en get. si form en post alors use request au lieu de query

        $products = $productRepository->searchByTerm($term);
        
        
        return $this->render('front/search.html.twig', ['products' => $products]);
    }


    /**
     * @Route("front/like/product/{id}", name="product_like")
     */
    public function likeProduct($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface, LikeRepository $likeRepository)
    {
        $product = $productRepository->find($id);
        $user = $this->getUser();

        if(!$user)
        {
            return $this->json([
                'code' => 403,
                'message' => "Vous devez être connecté"
            ], 403);
        }

        if($product->isLikedByUser($user))
        {
            $like = $likeRepository->findOneBy([
                'product' => $product,
                'user' => $user
            ]);
            $entityManagerInterface->remove($like);
            $entityManagerInterface->flush();

            return$this->json([
                'code' => 200,
                'message' => "Le like a été supprimé",
                'likes' => $likeRepository->count(['product' => $product])
            ], 200);
        }

        $like = new Like();
        $like->setProduct($product);
        $like->setUser($user);

        $entityManagerInterface->persist($like);
        $entityManagerInterface->flush();

        return$this->json([
            'code' => 200,
            'message' => "Le like a été enregistré",
            'likes' => $likeRepository->count(['product' => $product])
        ], 200);

    }

}