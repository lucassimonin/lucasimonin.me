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

    public function findContents($filters = [], $orderBy = [])
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c, t')
            ->join('c.translations', 't');
        if (!empty($filters) > 0) {
            foreach ($filters as $key => $filter) {
                $parameter = substr($key, 2, strlen($key));
                $qb->andWhere($key . $filter[0] . ' :'.$parameter);
                $qb->setParameter($parameter, $filter[1]);
            }
        }
        if (!empty($orderBy)) {
            foreach ($orderBy as $sort => $order) {
                $qb->orderBy($sort, $order);
            }

        }
        return $qb->getQuery()->getResult();
    }
}
