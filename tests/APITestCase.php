<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\BrowserKit\AbstractBrowser;

abstract class APITestCase extends WebTestCase
{
    protected static $uri;
    protected static $method;
    protected static $response;
    protected static $content;

    /** @var AbstractBrowser $client */
    protected static $client;

    public static function getAuthUrl(): string {
        return  '/api/auth/login/';
    }

    protected static function getClient($reinitialize = false, array $options = [], array $server = [])
    {
        if (! static::$client || $reinitialize) {
            static::$client = static::createClient($options, $server);
        }
        static::$client->getKernel()->boot();

        # SET SERVER PARAMS
        static::$client->setServerParameter('CONTENT_TYPE', sprintf('application/json'));
//        static::$client->setServerParameter('HTTP_HOST', getenv('DEFAULT_HOST').'/api');
        return static::$client;
    }


    protected function setUp() : void
    {
        static::getClient();
        $this->loadFixtures($this->getFixtures());
    }

    protected function tearDown() : void
    {
        parent::tearDown();
        static::$client = null;
    }

    protected static function getEntityManager()
    {
        return static::$container->get('doctrine')->getManager();
    }

    protected function getFixtures() : array
    {
        return [];
    }

    protected function loadFixtures(array $fixtures = []) : void
    {
        $loader = new Loader();
        foreach ($fixtures as $fixture) {
            if (! is_object($fixture)) {
                $fixture = new $fixture();
            }

            if ($fixture instanceof ContainerAwareInterface) {
                $fixture->setContainer(static::$container);
            }

            $loader->addFixture($fixture);
        }

        $em = static::getEntityManager();
        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }

    public static function createAuthenticatedClient(string $email = null, string $password = null)
    {
        $client = static::getClient();
//        if($client->getServerParameter('HTTP_Authorization')) {
//            return  $client;
//        }

        $email = $email ?: getenv('TEST_USER_EMAIL');
        $password = $password ?: getenv('TEST_USER_PASSWORD');
        $credentials = ['email' => $email, 'password' => $password,];

        $client = static::getClient();
        $client->request('POST', APITestCase::getAuthUrl(), [], [], [], json_encode($credentials));
        $data = json_decode($client->getResponse()->getContent(), true);


        if(empty($data)) {
            #TODO Error from response
            throw new Exception('Auth client response empty!');
        }

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
        return $client;
    }

    public static function makeRequest(array $data = [], string $uri = '', string $method = ''): AbstractBrowser
    {
        $url = $uri ?: self::$uri;
        $method = $method ?: self::$method;

        $client = self::getClient();
        $client->request($method, '/api'.$url, [], [], [], json_encode($data));
        self::$response = $client->getResponse();
        self::$content = json_decode(self::$response->getContent(), true);
        return $client;
    }

    public static function makeRequestWithAuth(array $data = [], string $uri = '', string $method = ''): AbstractBrowser
    {
        $url = $uri ?: self::$uri;
        $method = $method ?: self::$method;

        $client = self::createAuthenticatedClient();
        $client->request($method, '/api'.$url, [], [], [], json_encode($data));

        #TODO Refresh Token
        self::$response = $client->getResponse();
        self::$content = json_decode(self::$response->getContent(), true);
        return $client;
    }
}
