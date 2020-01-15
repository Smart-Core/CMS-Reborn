<?php

namespace SmartCore\Bundle\MediaBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SmartCore\Bundle\MediaBundle\Entity\File;

class FileRepository extends ServiceEntityRepository
{
    /**
     * FileRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    /**
     * @param string $collection
     *
     * @return int
     */
    public function countByCollection(string $collection)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('count(e.id)')
            ->where('e.collection = :collection')
            ->setParameter('collection', $collection)
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param string $collection
     *
     * @return int
     */
    public function summarySize(string $collection)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('sum(e.size)')
            ->where('e.collection = :collection')
            ->setParameter('collection', $collection)
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }
}
