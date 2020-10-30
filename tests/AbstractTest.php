<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractTest extends WebTestCase
{
    public const USER_EMAIL = 'ignashov-roman@mail.ru';
    public const STRANGER_EMAIL = 'test0@mail.com';
    protected $url;
    protected $uri;
    protected $method;
    protected $response;
    protected $content;

    public function getUrl(): string
    {
        return  $this->url.$this->uri;
    }

    /** @var AbstractBrowser $client */
    protected static $client;

    protected static function getClient($reinitialize = false, array $options = [], array $server = [])
    {
        if (! static::$client || $reinitialize) {
            static::$client = static::createClient($options, $server);
        }

        // core is loaded (for tests without calling of getClient(true))
        static::$client->getKernel()->boot();

        return static::$client;
    }

    protected function setUp() : void
    {
        static::getClient();
        $this->url = getenv('DEFAULT_HOST').'/api';
        $this->loadFixtures($this->getFixtures());
    }

    protected function tearDown() : void
    {
        parent::tearDown();
        static::$client = null;
    }

    /**
     * Shortcut
     */
    protected static function getEntityManager()
    {
        return static::$container->get('doctrine')->getManager();
    }

    /**
     * List of fixtures for certain test
     */
    protected function getFixtures() : array
    {
        return [];
    }

    /**
     * Load fixtures before test
     *
     * @param array $fixtures
     */
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

    public function assertResponseOk(?Response $response = null, ?string $message = null, string $type = 'text/html') : void
    {
        $this->failOnResponseStatusCheck($response, 'isOk', $message, $type);
    }

    public function assertResponseRedirect(
        ?Response $response = null,
        ?string $message = null,
        string $type = 'text/html'
    ) : void {
        $this->failOnResponseStatusCheck($response, 'isRedirect', $message, $type);
    }

    public function assertResponseNotFound(
        ?Response $response = null,
        ?string $message = null,
        string $type = 'text/html'
    ) : void {
        $this->failOnResponseStatusCheck($response, 'isNotFound', $message, $type);
    }

    public function assertResponseForbidden(
        ?Response $response = null,
        ?string $message = null,
        string $type = 'text/html'
    ) : void {
        $this->failOnResponseStatusCheck($response, 'isForbidden', $message, $type);
    }

    public function assertResponseCode(
        int $expectedCode,
        ?Response $response = null,
        ?string $message = null,
        string $type = 'text/html'
    ) : void {
        $this->failOnResponseStatusCheck($response, $expectedCode, $message, $type);
    }

    public function guessErrorMessageFromResponse(Response $response, string $type = 'text/html') : string
    {
        try {
            $crawler = new Crawler();
            $crawler->addContent($response->getContent(), $type);
            if (! count($crawler->filter('title'))) {
                $add = '';
                $content = $response->getContent();
                if ($response->headers->get('Content-Type') === 'application/json') {
                    $data = json_decode($content);
                    if ($data) {
                        $content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        $add = ' FORMATTED';
                    }
                }

                $title = '[' . $response->getStatusCode() . ']' . $add . ' - ' . $content;
            } else {
                $title = $crawler->filter('title')->text();
            }
        } catch (Throwable $e) {
            $title = $e->getMessage();
        }

        return trim($title);
    }

    private function failOnResponseStatusCheck(
        ?Response $response = null,
        $callback = null,
        ?string $message = null,
        string $type = 'text/html'
    ) : void {
        if ($callback === null) {
            $callback = 'isOk';
        }

        if ($response === null && self::$client) {
            $response = self::$client->getResponse();
        }

        try {
            if (is_int($callback)) {
                $this->assertEquals($callback, $response->getStatusCode());
            } else {
                $this->assertTrue($response->{$callback}());
            }

            return;
        } catch (Throwable $e) {
            // nothing to do
        }

        $err = $this->guessErrorMessageFromResponse($response, $type);
        if ($message) {
            $message = rtrim($message, '.') . '. ';
        }

        if (is_int($callback)) {
            $template = 'Failed asserting Response status code %s equals %s.';
        } else {
            $template = 'Failed asserting that Response[%s] %s.';
            $callback = preg_replace('#([a-z])([A-Z])#', '$1 $2', $callback);
        }

        $message .= sprintf($template, $response->getStatusCode(), $callback, $err);
        $max_length = 100;
        if (mb_strlen($err, 'utf-8') < $max_length) {
            $message .= ' ' . $this->makeErrorOneLine($err);
        } else {
            $message .= ' ' . $this->makeErrorOneLine(mb_substr($err, 0, $max_length, 'utf-8') . '...');
            $message .= "\n\n" . $err;
        }

        $this->fail($message);
    }

    private function makeErrorOneLine($text)
    {
        return preg_replace('#[\n\r]+#', ' ', $text);
    }

    public function createAuthenticatedClient(string $startEmail = null, string $startPassword = null)
    {
        $email = $startEmail ? $startEmail : getenv('TEST_USER_EMAIL');
        $password = $startPassword ? $startPassword : getenv('TEST_USER_PASSWORD');
        $credentials = ['email' => $email, 'password' => $password,];

        $client = static::getClient();
        $client->request('POST', '/api/auth/login/', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($credentials));

        $data = json_decode($client->getResponse()->getContent(), true);

        if(empty($data)) {
            throw new \Exception('Auth client response empty!');
        }

        $client = static::getClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
        $client->setServerParameter('CONTENT_TYPE', sprintf('application/json'));

        return $client;
    }

    public function logout() : void
    {
        $client = static::getClient();
        $client->setServerParameter('HTTP_Authorization', sprintf(''));
    }

    public function makeRequest(array $data = [], string $url = '', string $method = ''): AbstractBrowser
    {
        $url = $url ? $this->url.$url : $this->getUrl();
        $method = $method ? $method : $this->method;
        $client = self::getClient();
        $client->request($method, $url, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $this->response = $client->getResponse();
        $this->content = json_decode($this->response->getContent(), true);
        return $client;
    }

    public function makeRequestWithAuth(array $data = [], string $url = '', string $method = ''): AbstractBrowser
    {
        $url = $url ? $this->url.$url : $this->getUrl();
        $method = $method ? $method : $this->method;
        $client = $this->createAuthenticatedClient();
        $client->request($method, $url, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $this->response = $client->getResponse();
        $this->content = json_decode($this->response->getContent(), true);
        return $client;
    }
}
