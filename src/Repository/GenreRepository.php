<?php

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function findFirstTen(int $id){
        $em = $this->getEntityManager();

        /* DQL Method */
        $dql = "SELECT genre FROM App\Entity\Genre AS genre INNER JOIN App\Entity\TypeMedia AS type WHERE genre.id = type.id AND type.id = :id";
        $query = $em->createQuery($dql);

        $query->setParameter('id', $id);

        // Définition d'où je récupère mes lignes
        $query->setFirstResult(0);

        // Nombre de lignes récupèrées
        $query->setMaxResults(10);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        foreach ($paginator as $post) {
            echo $post->getHeadline() . "\n";
        }

        return $query->getResult();
    }

    public function findByTypeMedia($idType){
        return $this->createQueryBuilder('g')
            ->innerJoin('g.typeMedia','type')
            ->where('type.id = :id')
            ->setParameter('id',$idType)
            ->getQuery()
            ->getResult()
            ;
    }
}
