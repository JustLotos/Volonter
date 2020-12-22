<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Reset\ByEmail;

use App\DataFixtures\Helper\VolunteerFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\RawMessage;

class ConfirmActionTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/user/reset/email/confirm';

    public function getFixtures() : array
    {
        return [VolunteerFixtures::class];
    }

    public function testValid()
    {
        $this->makeRequest([
            'email' => getenv('TEST_USER_EMAIL'),
            'password' => '12345678Ab',
            'plainPassword' => '12345678Ab'
        ], '/user/reset/email/request/', 'POST');

        /** @var RawMessage $email */
        $email = self::getMailerMessage(0);

        $crawler = new Crawler($email->serialize());
        $code = $crawler->filter('a.confirm-link')->attr('data-token');

        $client = $this->makeRequest([], "$this->uri/$code/");
        self::assertTrue($this->response->isRedirect());

        /** @var Response $response */
        $response = $client->getResponse();
        $content = new Crawler($response->getContent());


        $link = $content->filter('a[href="/?resetByEmail=confirm"]');
        self::assertEquals('/?resetByEmail=confirm', $link->text());
    }

    public function testNotExistingValue()
    {
        $client = $this->makeRequest([], $this->uri.'/123/');
        /** @var Response $response */
        $response = $client->getResponse();
        $content = $response->getContent();

        var_dump($response);
        self::assertResponseNotFound($response);
        self::assertEquals('Invalid or not found token.', $content);
    }
}
