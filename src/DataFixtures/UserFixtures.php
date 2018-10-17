<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 13/04/2018
 * Time: 13:41
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var ObjectManager */
    private $manager;

    private $encoder;

    private $authenticator;

    public function __construct(UserPasswordEncoderInterface $encoder, GoogleAuthenticatorInterface $authenticator)
    {
        $this->encoder = $encoder;
        $this->authenticator = $authenticator;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $user = $this->addUser(array(
            'username' => 'admin',
            'firstname'=> 'admin',
            'lastname' => 'admin'.'ing',
            'email'    => 'admin@gmail.com',
            'password' => 'admin',
            'role'     => 'ROLE_SUPER_ADMIN',
            'enabled'  => true,
            'secret'   => $this->authenticator->generateSecret()
        ));
        $this->addReference('admin-user', $user);
    }

    public function addUser($settings)
    {
        $user = new User($settings['enabled']);
        $user->setUsername($settings['username']);
        $user->setEmail($settings['email']);
        $user->setFirstName($settings['firstname']);
        $user->setLastName($settings['lastname']);
        $password = $this->encoder->encodePassword($user, $settings['password']);
        $user->setPassword($password);

        if (isset($settings['role'])) {
            $user->addRole($settings['role']);
        }
        $this->manager->persist($user);
        $this->manager->flush();

        $this->addReference('user-'.strtolower($settings['username']), $user);

        return $user;
    }
}
