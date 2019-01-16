<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 15/04/2018
 * Time: 15:36
 */

namespace App\Command;

use App\Entity\User;
use App\Services\User\UserManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateQRCodeMFACommand extends Command
{
    protected static $defaultName = 'app:generate:qrcode-mfa';

    /**
     * @var UserManagerInterface
     */
    private $userManager;
    /**
     * @var string
     */
    private $pathKernel;
    /**
     * @var GoogleAuthenticatorInterface
     */
    private $googleAuthenticator;


    /**
     * GenerateQRCodeMFACommand constructor.
     * @param UserManagerInterface $userManager
     * @param string $pathKernel
     * @param GoogleAuthenticatorInterface $googleAuthenticator
     */
    public function __construct(UserManagerInterface $userManager, string $pathKernel, GoogleAuthenticatorInterface $googleAuthenticator)
    {
        $this->userManager = $userManager;
        $this->pathKernel = $pathKernel;
        $this->googleAuthenticator = $googleAuthenticator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate QR Code for MFA')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var User $user */
        foreach ($this->userManager->findAll() as $user) {
            if ('' === $user->getGoogleAuthenticatorSecret()) {
                $user->setGoogleAuthenticatorSecret($this->googleAuthenticator->generateSecret());
                $this->userManager->save($user);
            }
            $url = $this->googleAuthenticator->getUrl($user);
            $output->writeln($url);
            file_put_contents(sprintf('%s/var/qrcodes/%s.png', $this->pathKernel, $user->getEmail()), file_get_contents($url));
        }
    }
}
