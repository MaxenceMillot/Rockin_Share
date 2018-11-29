<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Media::class);
    }

    // /**
    //  * @return Media[] Returns an array of Media objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneOrNullByGenre($idGenre)
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.genres','g')
            ->where('g.id = :id')
            ->setParameter('id', $idGenre)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneOrNullByUser($idUser)
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.utilisateur','u')
            ->where('u.id = :id')
            ->setParameter('id', $idUser)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findAllByUser($idUser)
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.utilisateur','u')
            ->where('u.id = :id')
            ->setParameter('id', $idUser)
            ->addOrderBy('m.dateCreated', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
