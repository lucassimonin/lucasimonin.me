<?php

/**
 * Service
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Services\User;

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
class UserManager implements UserManagerInterface
{
    private $passwordEncoder;

    private $manager;

    private $repository;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface       $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
        $this->repository = $this->manager->getRepository(User::class);
    }


    /**
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public function save(User $user): User
    {
        // Save user
        $user->setUpdated(new \DateTime());
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }

    /**
     * @param User $user
     */
    public function remove(User $user)
    {
        $this->manager->remove($user);
        $this->manager->flush();
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }
}
