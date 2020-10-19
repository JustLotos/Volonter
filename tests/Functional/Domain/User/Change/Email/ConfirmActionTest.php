<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Change\Email;

use App\DataFixtures\User\UserFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\RawMessage;

class ConfirmActionTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/user/change/email/confirm';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid()
    {
        $this->makeRequestWithAuth([
            'oldEmail' => getenv('TEST_USER_EMAIL'),
            'newEmail' => 'test@test.jest'
        ], '/user/change/email/request/', 'POST');

        /** @var RawMessage $email */
        $email = self::getMailerMessage(0);

        $crawler = new Crawler($email->serialize());
        $code = $crawler->filter('a.confirm-link')->attr('data-token');

        $client = $this->makeRequest([], $this->uri.'/'.$code);

        self::assertTrue($this->response->isRedirect());

        /** @var Response $response */
        $response = $client->getResponse();
        $content = new Crawler($response->getContent());

        $link = $content->filter('a[href="/?changeEmail=confirm"]');
        self::assertEquals('/?changeEmail=confirm', $link->text());
    }

    public function testNotExistingValue()
    {
        $client = $this->makeRequestWithAuth([], $this->uri.'/123');
        /** @var Response $response */
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertResponseNotFound($response);
        self::assertEquals('Invalid or not found token.', $content);
    }
}
