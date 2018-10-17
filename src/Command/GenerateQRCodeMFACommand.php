<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 15/04/2018
 * Time: 15:36
 */

namespace App\Command;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateQRCodeMFACommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:generate:qrcode-mfa')
            ->setDescription('Generate QR Code for MFA')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $users = $doctrine->getRepository(User::class)->findAll();
        /** @var User $user */
        $googleAuthenticator = $this->getContainer()->get("scheb_two_factor.security.google_authenticator");
        foreach ($users as $user) {
            if ('' === $user->getGoogleAuthenticatorSecret()) {
                $user->setGoogleAuthenticatorSecret($googleAuthenticator->generateSecret());
                $doctrine->getManager()->persist($user);
                $doctrine->getManager()->flush();
            }
            $url = $googleAuthenticator->getUrl($user);
            $output->writeln($url);
            file_put_contents(sprintf('%s/var/qrcodes/%s.png', $this->getContainer()->getParameter('kernel.project_dir'), $user->getEmail()), file_get_contents($url));
        }
    }
}
