<?php

namespace App\Repository;

use App\Entity\Eleve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Eleve|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eleve|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eleve[]    findAll()
 * @method Eleve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EleveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eleve::class);
    }

    // /**
    //  * @return Eleve[] Returns an array of Eleve objects
    //  */
    public function findByStatut($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.statut = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByNom($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nom = :val', 'e.statut = :val1')
            ->setParameter('val', $value)
            ->setParameter('val1', true)
            ->orderBy('e.id', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByPrenom($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.prenom = :val', 'e.statut = :val1')
            ->setParameter('val', $value)
            ->setParameter('val1', true)
            ->orderBy('e.id', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByMatricule($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.matricule = :val', 'e.statut = :val1')
            ->setParameter('val', $value)
            ->setParameter('val1', true)
            ->orderBy('e.id', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByClasse($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.classe = :val', 'e.statut = :val1')
            ->setParameter('val', $value)
            ->setParameter('val1', true)
            ->orderBy('e.id', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Eleve
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
