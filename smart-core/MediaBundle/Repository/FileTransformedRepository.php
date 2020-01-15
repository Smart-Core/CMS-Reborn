<?php

namespace SmartCore\Bundle\MediaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SmartCore\Bundle\MediaBundle\Entity\FileTransformed;

class FileTransformedRepository extends EntityRepository
{
    /**
     * FileTransformedRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileTransformed::class);
    }

    /**
     * @param Collection|int $collection
     *
     * @return int
     */
    public function countByCollection($collection)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('count(e.id)')
            ->where('e.collection = :collection')
            ->setParameter('collection', $collection)
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param Collection|int $collection
     *
     * @return int
     */
    public function summarySize($collection)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('sum(e.size)')
            ->where('e.collection = :collection')
            ->setParameter('collection', $collection)
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }
}
