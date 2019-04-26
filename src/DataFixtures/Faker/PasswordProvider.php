<?php


namespace App\DataFixtures\Faker;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordProvider
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function password($user, $password)
    {
        return $this->encoder->encodePassword($user, $password);
    }
}
