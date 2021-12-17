<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


    public function searchByTerm($term)
    {
        // queryBuilder permet de creer des requetes sql en php
        $queryBuilder = $this->createQueryBuilder('product');

        // requete en question
        $query = $queryBuilder
                ->select('product')
                ->leftJoin('product.type', 'type')      // leftJoin sur la table Type
                ->leftJoin('product.brand', 'brand')    // leftJoin sur la table Brand
                ->where('product.name LIKE :term' )     //WHERE en SQL
                ->orWhere('product.description LIKE :term')
                ->orWhere('type.name LIKE :term')
                ->orWhere('type.description LIKE :term')
                ->orWhere('brand.name LIKE :term')
                ->orWhere('brand.description LIKE :term')
                ->setParameter('term', '%' . $term . '%') // on attribut le term rentré et on securise
                ->getQuery();

                return $query->getResult();
            }


    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
