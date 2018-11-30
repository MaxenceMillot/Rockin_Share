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

    public function findAllOrder(){
        return $this->createQueryBuilder('m')
            ->innerJoin('m.genres','g')
            ->innerJoin('g.typeMedia','t')
            ->addOrderBy('m.dateCreated', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

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
