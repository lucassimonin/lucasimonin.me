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
class UserService
{
    private $passwordEncoder;

    private $manager;

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
        $this->manager->persist($user);
        $this->manager->flush();
    }

    /**
     * @param User $user
     */
    public function remove(User $user)
    {
        $this->manager->remove($user);
        $this->manager->flush();
    }
}
