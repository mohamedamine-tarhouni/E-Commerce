<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Produit $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Produit $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
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
    public function getProductsByName($saisie)
    {
        // createQueryBuilder() permet de créer une requête SQL
        // elle prend en arg un alias qui représente la table

        return $this->createQueryBuilder('a')
                    ->andWhere('a.nom LIKE :val')
                    ->setParameter('val', "%$saisie%")
                    ->orderBy('a.nom', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
    public function getProducts()
    {
        // createQueryBuilder() permet de créer une requête SQL
        // elle prend en arg un alias qui représente la table

        return $this->createQueryBuilder('a')
                    ->orderBy('a.nom', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
    public function getUserProducts($id)
    {
        // createQueryBuilder() permet de créer une requête SQL
        // elle prend en arg un alias qui représente la table

        return $this->createQueryBuilder('a')
                    ->andWhere('a.User = :id')
                    ->setParameter('id', $id)
                    ->orderBy('a.nom', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    public function geteditedproduct($prodid)
    {
        // createQueryBuilder() permet de créer une requête SQL
        // elle prend en arg un alias qui représente la table

        return $this->createQueryBuilder('a')
                    ->andWhere('a.id= :prodid')
                    ->setParameter('prodid', $prodid)
                    ->orderBy('a.nom', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
    /*
    public function findOneBySomeField($value): ?Produit
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
