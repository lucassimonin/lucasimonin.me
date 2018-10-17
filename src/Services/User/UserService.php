<?php

/**
 * Service
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Services\User;

use App\Services\Core\BaseService;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserService
 * `
 * Object manager of user
 *
 * @package App\Services\User
 */
class UserService extends BaseService
{
    private $passwordEncoder;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface       $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($em);
        $this->addRepository('userRepository', User::class);
        $this->passwordEncoder = $passwordEncoder;
    }


    /**
     * Save a user
     *
     * @param User $user
     */
    public function save(User $user)
    {
        // Save user
        $user->setUpdated(new \DateTime());
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function remove(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * Get all user
     *
     * @param array $filters
     *
     * @return mixed
     */
    public function queryForSearch($filters = array())
    {
        return $this->userRepository->queryForSearch($filters);
    }

    /**
     * Find all
     *
     * @return mixed
     */
    public function findAll()
    {
        return $this->userRepository->findAll();
    }

    /**
     * Find one by
     *
     * @param array $filters
     * @return mixed
     */
    public function findOneBy($filters = array())
    {
        return $this->userRepository->findOneBy($filters);
    }


}
