<?php

namespace App\Repository;

use App\Entity\VinylMix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VinylMix>
 *
 * @method VinylMix|null find($id, $lockMode = null, $lockVersion = null)
 * @method VinylMix|null findOneBy(array $criteria, array $orderBy = null)
 * @method VinylMix[]    findAll()
 * @method VinylMix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinylMixRepository extends ServiceEntityRepository
{
    const ALIAS = "mix";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VinylMix::class);
    }

    public function findByGenreOrderByVotes($genre = null)
    {
        $qb = $this->createQueryBuilderWithVotesOrder();

        if ($genre !== null) {
            $qb
                ->andWhere(self::ALIAS . '.genre = :genre')
                ->setParameter("genre", $genre);
        }
        return $qb
            ->getQuery()
            ->getResult();
    }

    private function createQueryBuilderWithVotesOrder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        if (null === $queryBuilder) {
            $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        }

        $queryBuilder->orderBy(self::ALIAS . '.votes', "DESC");

        return $queryBuilder;
    }

//    /**
//     * @return VinylMix[] Returns an array of VinylMix objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VinylMix
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
