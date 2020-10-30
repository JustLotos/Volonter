<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Register\ByEmail;

use App\DataFixtures\Helper\UserFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\RawMessage;

class ConfirmActionTest extends AbstractTest
{
    protected $uri = '/user/register/email/confirm/';
    protected $method = 'GET';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid() : void
    {
        $this->makeRequest([
            'email' => 'test@test.com',
            'password' => '12345678Ab',
            'plainPassword' => '12345678Ab',
        ], '/user/register/email/request/', 'POST');

        self::assertResponseOk($this->response);
        self::assertEmailCount(1);

        /** @var RawMessage $email */
        $email = self::getMailerMessage(0);
        self::assertEmailHeaderSame($email, 'To', 'test@test.com');

        $crawler = new Crawler($email->serialize());
        $code = $crawler->filter('a#confirm-link')->attr('data-token');
        self::assertIsString($code);

        $this->makeRequest([], $this->uri.$code.'/');

        $content = new Crawler($this->response->getContent());
        $link = $content->filter('a[href="/?registerByEmail=confirm"]');

        self::assertTrue($this->response->isRedirect());
        self::assertEquals('/?registerByEmail=confirm', $link->text());
    }


    public function testNonExistingToken() : void
    {
        $client = $this->makeRequest([], $this->uri.'123/');

        /** @var Response $response */
        $response = $client->getResponse();

        self::assertResponseNotFound($response);
        self::assertEquals('Incorrect or confirmed token.', $response->getContent());
    }
}
