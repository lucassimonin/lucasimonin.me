<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 2018-12-26
 * Time: 22:54
 */

namespace App\Tests\Framework;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client as PantherClient;
use Symfony\Component\Panther\PantherTestCase as BasePantherTestCase;
use Throwable;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Doctrine\ORM\Tools\SchemaTool;

class PantherTestCase extends BasePantherTestCase
{
    //use RefreshDatabaseTrait;

    /** @var EntityManagerInterface */
    protected $em = null;
    /** @var PantherClient */
    protected $client = null;

    /** @var Crawler */
    protected $crawler = null;

    /**
     * @var string[] The list of bundles where to look for fixtures
     */
    protected static $bundles = [];

    /**
     * @var bool Append fixtures instead of purging
     */
    protected static $append = false;

    /**
     * @var bool Use TRUNCATE to purge
     */
    protected static $purgeWithTruncate = true;

    /**
     * @var string|null The name of the Doctrine shard to use
     */
    protected static $shard = null;

    protected static $firewallName = 'main';

    protected static $folderScreenShot = 'screenshots';

    // if you don't define multiple connected firewalls, the context defaults to the firewall name
    // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
    protected static $firewallContext = 'main';

    protected static $defaultUserMail = 'user@cv.com';

    protected static $defaultAdminMail = 'admin@cv.com';


    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createPantherClient([
            'port' => 9009,
        ],
        [
            'environment' => 'test'
        ]);
        $this->client->followRedirects(true);
        $container = self::$container;
        $this->em = $container->get('doctrine')->getManager();
        static $load = false;
        if (!$load) {
            if (!isset($metadata)) {
                $metadata = $this->em->getMetadataFactory()->getAllMetadata();
            }
            $schemaTool = new SchemaTool($this->em);
            $schemaTool->dropDatabase();
            if (!empty($metadata)) {
                $schemaTool->createSchema($metadata);
                $container->get('hautelook_alice.loader')->load(
                    new Application(static::$kernel), // OK this is ugly... But there is no other way without redesigning LoaderInterface from the ground.
                    $this->em,
                    static::$bundles,
                    static::$kernel->getEnvironment(),
                    static::$append,
                    static::$purgeWithTruncate,
                    static::$shard
                );
            }
            $load = true;
        }
    }

    /**
     * @param $form
     *
     * @return $this
     */
    protected function submitForm($form): self
    {
        $this->crawler = $this->client->submit($form);

        return $this;
    }

    /**
     * @param Throwable $t
     */
    protected function onNotSuccessfulTest(Throwable $t)
    {
        $exception = '';
        $throwableClass = get_class($t);
        if ($this->crawler && $this->crawler->filter('.exception-message')->count()) {
            $exception = ' | ' . $this->crawler->filter('.exception-message')->eq(0)->text();
        }

        throw new $throwableClass($t->getMessage() . $exception);
    }

    /**
     * @param string $url
     * @param string $method
     *
     * @return $this
     */
    public function visit(string $url, string $method = 'GET'): self
    {
        $this->crawler = $this->client->request($method, $url);

        return $this;
    }

    public function takeScreenShot(string $name): void
    {
        $this->client->takeScreenshot(sprintf('%s/%s.png', static::$folderScreenShot, $name));
    }

    protected function logIn(?string $email = null): void
    {
        $session = self::$container->get('session');

        if (null === $email) {
            $email = static::$defaultUserMail;
        }
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => $email]);

        $token = new UsernamePasswordToken($user, $user->getPassword(), static::$firewallName, $user->getRoles());
        $session->set('_security_'.static::$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function tearDown(): void
    {
        $this->client = null;
        static::$kernel->shutdown();
        $this->em->close();
        $this->em = null;
        static::$container = null;
        $this->crawler = null;
    }
}
