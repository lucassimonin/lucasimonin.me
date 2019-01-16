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
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Doctrine\ORM\Tools\SchemaTool;

class WebTestCase extends BaseWebTestCase
{
    //use RefreshDatabaseTrait;

    /** @var EntityManagerInterface */
    protected $em = null;
    /** @var Client */
    protected $client = null;

    /** @var Crawler */
    protected $crawler = null;

    /** @var Response */
    protected $response = null;

    /** @var string */
    protected $content = null;

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

    protected static $firewallName = 'shop';

    // if you don't define multiple connected firewalls, the context defaults to the firewall name
    // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
    protected static $firewallContext = 'shop';

    protected static $defaultUserMail = 'user@sylius.com';


    protected function setUp()
    {
        parent::setUp();
        $this->client = self::createClient([
            'environment' => 'test'
        ]);
        $this->client->followRedirects(true);
        $container = $this->client->getContainer();
        $this->em = $container->get('doctrine')->getManager();
        static $load = false;
        if (!$load) {
            if (!isset($metadatas)) {
                $metadatas = $this->em->getMetadataFactory()->getAllMetadata();
            }
            $schemaTool = new SchemaTool($this->em);
            $schemaTool->dropDatabase();
            if (!empty($metadatas)) {
                $schemaTool->createSchema($metadatas);
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
    protected function submitForm($form)
    {
        $this->crawler = $this->client->submit($form);
        $this->response = $this->client->getResponse();
        $this->content = $this->response->getContent();

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
    public function visit(string $url, string $method = 'GET'): WebTestCase
    {
        $this->crawler = $this->client->request($method, $url);
        $this->response = $this->client->getResponse();
        $this->content = $this->response->getContent();

        return $this;
    }

    /**
     * @return $this
     */
    public function responseOk()
    {
        $this->assertSame(Response::HTTP_OK, $this->response->getStatusCode());

        return $this;
    }

    public function seeText(string $text)
    {
        $this->assertContains($text, $this->content);

        return $this;
    }

    protected function logIn(?string $email = null): void
    {
        $session = $this->client->getContainer()->get('session');

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

    protected function tearDown()
    {
        $this->client = null;
        static::$kernel->shutdown();
        $this->em->close();
        $this->em = null;
        static::$container = null;
        $this->response = null;
        $this->content = null;
        $this->crawler = null;
    }
}
