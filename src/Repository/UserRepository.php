<?php

/**
 * Repository
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Repository;

use App\Model\SearchInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * Class UserRepository
 *
 * @package App\Repository
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{

    /**
     * Get all user query, using for pagination
     *
     * @param SearchInterface $search
     * @return mixed
     */
    public function queryForSearch(SearchInterface $search)
    {
        $filters = $search->getFilters();
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->orderBy('u.lastName', 'asc');

        foreach ($filters as $key => $filter) {
            if (null === $filter) {
                continue;
            }
            $qb->andWhere('u.'.$key.' LIKE :'.$key);
            $qb->setParameter($key, '%'.$filter.'%');
        }

        return $qb->getQuery();
    }

    /**
     * @param string $username
     * @return mixed|null|\Symfony\Component\Security\Core\User\UserInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
                    ->where('u.username = :username OR u.email = :email')
                    ->setParameter('username', $username)
                    ->setParameter('email', $username)
                    ->getQuery()
                    ->getOneOrNullResult();
    }
}
