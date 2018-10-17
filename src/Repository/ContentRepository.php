<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ContentRepository
 *
 * @package App\Repository
 */
class ContentRepository extends EntityRepository
{
    /**
     * @param array $filters
     * @return mixed
     */
    public function queryForSearch($filters = array())
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.translations', 't');
        if (count($filters) > 0) {
            foreach ($filters as $key => $filter) {
                $qb->andWhere('t.'.$key.' LIKE :'.$key);
                $qb->setParameter($key, '%'.$filter.'%');
            }
        }

        return $qb->getQuery();
    }
}
