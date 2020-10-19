<?php

declare(strict_types=1);

namespace App\Tests\CardController;

use App\DataFixtures\CardFixture;
use App\DataFixtures\DeckFixture;
use App\DataFixtures\UserFixtures;
use App\Entity\Card;
use App\Entity\User;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class GetActionTest extends AbstractTest
{
    private $method = 'GET';
    private $uri = '/cards/';

    public function getFixtures() : array
    {
        return [
            UserFixtures::class,
            DeckFixture::class,
            CardFixture::class
        ];
    }

    public function setUp() : void
    {
        parent::setUp();
    }

    public function testGetDeckValid() : void
    {
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $cardsOwner = self::getEntityManager()->getRepository(Card::class)->getAll($user);

        $client = $this->makeRequest($this->method, $this->uri.$cardsOwner[0]->getId(), []);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertResponseOk($response);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('first_repeat_at', $content);
        $this->assertArrayHasKey('next_repeat_at', $content);
        $this->assertArrayHasKey('created_at', $content);
        $this->assertArrayHasKey('updated_at', $content);
        $this->assertArrayHasKey('count_repeat', $content);
        $this->assertArrayHasKey('records', $content);
        $this->assertArrayHasKey('id', $content['records'][0]);
        $this->assertArrayHasKey('content', $content['records'][0]);
        $this->assertArrayHasKey('side', $content['records'][0]);
    }

    public function testGetDeckStrangerAccess() : void
    {
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::STRANGER_EMAIL]);
        $cardsStranger = self::getEntityManager()->getRepository(Card::class)->getAll($user);

        $client = $this->makeRequest($this->method, $this->uri.$cardsStranger[0]->getId(), []);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertResponseCode(Response::HTTP_NOT_FOUND, $response);
        $this->assertArrayHasKey('errors', $content);
        $this->assertArrayHasKey('message', $content['errors']);
    }

    public function testGetDeckNotFound() : void
    {
        $client = $this->makeRequest($this->method, $this->uri.'invalid', []);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertResponseCode(Response::HTTP_NOT_FOUND, $response);
        $this->assertArrayHasKey('errors', $content);
        $this->assertArrayHasKey('message', $content['errors']);
    }
}
