<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class MediaRepository
 *
 * @package App\Repository
 */
class MediaRepository extends EntityRepository
{

    /**
     * Get all user query, using for pagination
     *
     * @param array $filters
     *
     * @return mixed
     */
    public function queryForSearch($filters = array())
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->orderBy('m.created', 'desc');

        if (count($filters) > 0) {
            foreach ($filters as $key => $filter) {
                if ($key == 'search') {
                    $qb->andWhere('(m.legend LIKE :legend OR m.size LIKE :size OR m.alt LIKE :alt OR m.pathMedia LIKE :pathMedia OR m.mimetype LIKE :mimetype OR m.title LIKE :title OR m.description LIKE :description)');
                    $qb->setParameter('pathMedia', '%'.$filter.'%');
                    $qb->setParameter('mimetype', '%'.$filter.'%');
                    $qb->setParameter('title', '%'.$filter.'%');
                    $qb->setParameter('description', '%'.$filter.'%');
                    $qb->setParameter('legend', '%'.$filter.'%');
                    $qb->setParameter('size', '%'.$filter.'%');
                    $qb->setParameter('alt', '%'.$filter.'%');
                }


                if ($key == 'accepted') {
                    if ($filter == 'images/*') {
                        $qb->andWhere('(m.mimetype LIKE :png OR m.mimetype LIKE :gif OR m.mimetype LIKE :jpg OR m.mimetype LIKE :jpeg)');
                        $qb->setParameter('png', '%png%');
                        $qb->setParameter('gif', '%gif%');
                        $qb->setParameter('jpg', '%jpg%');
                        $qb->setParameter('jpeg', '%jpeg%');
                    } else {
                        $qb->andWhere('m.mimetype LIKE :accepted');
                        $qb->setParameter('accepted', '%'.$filter.'%');
                    }
                }
            }
        }

        return $qb->getQuery();
    }


    public function findByIds($ids)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m');

        if (empty($ids)) {
            $ids = [0];
        }
        $qb->add('where', $qb->expr()->in('m.id', $ids));

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
