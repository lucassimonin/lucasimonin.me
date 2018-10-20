<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class CreateUserCommand
 * @package App\Command
 */
class CreateUserCommand extends Command
{

    /** @var ManagerRegistry  */
    private $doctrine;

    /** @var UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /**
     * CreateUserCommand constructor.
     * @param $doctrine
     * @param $passwordEncoder
     */
    public function __construct(ManagerRegistry $doctrine, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->doctrine = $doctrine;
        $this->passwordEncoder = $passwordEncoder;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('app:create-user')
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user.')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'create admin user.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $userRepository = $this->doctrine->getRepository(User::class);
        $io->title('USER CREATOR');
        $admin = $input->getOption('admin');
        $userEntity = new User(true);
        $userEntity->addRole($admin ? 'ROLE_SUPER_ADMIN' : 'ROLE_USER');
        do {
            $username = $io->ask('Username', null, function ($username) {
                if (empty($username)) {
                    throw new \RuntimeException('Username cannot be empty.');
                }

                return $username;
            });
            $user = $userRepository->findOneByUsername($username);
            if (null !== $user) {
                $io->warning('This username is already used!');
            }
        } while (null !== $user);

        do {
            $email = $io->ask('Email', null, function ($email) {
                if (empty($email)) {
                    throw new \RuntimeException('Email cannot be empty.');
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \RuntimeException('Enter a valid email.');
                }

                return $email;
            });
            $user = $userRepository->findOneByEmail($email);
            if (null !== $user) {
                $io->warning('This email is already used!');
            }
        } while (null !== $user);

        $password = $io->askHidden('Password', function ($password) {
            if (empty($password)) {
                throw new \RuntimeException('Password cannot be empty.');
            }
            if (!preg_match(User::PATTERN_EMAIL, $password)) {
                throw new \RuntimeException('The password must be at least 8 characters long, an uppercase character, a lowercase character, a number, and a non-alphabetic character.');
            }

            return $password;
        });

        $userEntity->setEmail($email);
        $userEntity->setPlainPassword($password);
        $userEntity->setUsername($username);

        $password = $this->passwordEncoder->encodePassword($userEntity, $userEntity->getPlainPassword());
        $userEntity->setPassword($password);
        $manager = $this->doctrine->getManager();
        $manager->persist($userEntity);
        $manager->flush();
        $io->success(
            [
                'user created!',
                'username: ' . $username,
                'password: ' . $userEntity->getPlainPassword()
            ]
        );
    }
}
