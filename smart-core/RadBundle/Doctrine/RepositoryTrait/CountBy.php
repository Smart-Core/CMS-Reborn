<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\RepositoryTrait;

trait CountBy
{
    public function countBy(array $criteria = null): int
    {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->createQueryBuilder('e');
        $qb->select('count(e.id)');

        $firstCriteria = true;
        if (!empty($criteria)) {
            foreach ($criteria as $field => $value) {
                if ($firstCriteria) {
                    if ($value === null or $value === 'null') {
                        $qb->where("e.$field IS NULL");
                    } elseif ($value === 'not null') {
                        $qb->where("e.$field IS NOT NULL");
                    } else {
                        $qb->where("e.$field = :$field");
                        $qb->setParameter($field, $value);
                    }

                    $firstCriteria = false;
                } else {
                    if ($value === null or $value === 'null') {
                        $qb->andWhere("e.$field IS NULL");
                    } elseif ($value === 'not null') {
                        $qb->andWhere("e.$field IS NOT NULL");
                    } else {
                        $qb->andWhere("e.$field = :$field");
                        $qb->setParameter($field, $value);
                    }
                }
            }
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}
