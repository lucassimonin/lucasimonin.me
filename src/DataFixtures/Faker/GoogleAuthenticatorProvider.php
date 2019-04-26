<?php


namespace App\DataFixtures\Faker;

use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;

class GoogleAuthenticatorProvider
{
    /**
     * @var GoogleAuthenticatorInterface
     */
    private $authenticator;

    public function __construct(GoogleAuthenticatorInterface $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function googleAuthenticator()
    {
        return $this->authenticator->generateSecret();
    }
}
