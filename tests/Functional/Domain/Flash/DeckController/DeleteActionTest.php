<?php

declare(strict_types=1);

namespace App\Tests\DeckController;

use App\DataFixtures\DeckFixture;
use App\DataFixtures\UserFixtures;

use App\Entity\Deck;
use App\Entity\User;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class DeleteActionTest extends AbstractTest
{
    private $method = 'DELETE';
    private $uri = '/decks/';

    protected function getFixtures() : array
    {
        return [
            UserFixtures::class,
            DeckFixture::class,
        ];
    }

    protected function setUp() : void
    {
        parent::setUp();
    }

    public function testPutDeckValid() : void
    {
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['user' => $user]);

        $client = $this->makeRequest($this->method, $this->uri.$deck->getId(), []);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertResponseOk($response);
        static::assertArrayHasKey('success', $content);
    }

    public function testPutDeckInvalidKey() : void
    {
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::STRANGER_EMAIL]);
        $deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['user' => $user]);
        $client = $this->makeRequest($this->method, $this->uri.$deck->getId(), []);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertResponseNotFound($response);
        static::assertArrayHasKey('errors', $content);
        static::assertArrayHasKey('message', $content['errors']);
    }

    public function testPatchDeckNotFound() : void
    {
        $client = $this->makeRequest($this->method, $this->uri.'invalid', []);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertResponseNotFound($response);
        static::assertArrayHasKey('errors', $content);
        static::assertArrayHasKey('message', $content['errors']);
    }
}
