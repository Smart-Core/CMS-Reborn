<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Doctrine\RepositoryTrait;

use Doctrine\ORM\Query;

trait FindByQuery
{
    public function getFindByQuery(array $criteria = null, array $orderBy = null, $limit = null, $offset = null): Query
    {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->createQueryBuilder('e');

        $firstCriteria = true;
        if ( ! empty($criteria)) {
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

        $firstOrderBy = true;
        if ( ! empty($orderBy)) {
            foreach ($orderBy as $field => $value) {
                if ($firstOrderBy) {
                    $qb->orderBy("e.$field", $value);
                    $firstOrderBy = false;
                } else {
                    $qb->addOrderBy("e.$field", $value);
                }
            }
        }

        if ( ! empty($limit)) {
            $qb->setMaxResults($limit);
        }

        if ( ! empty($offset)) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery();
    }
}
