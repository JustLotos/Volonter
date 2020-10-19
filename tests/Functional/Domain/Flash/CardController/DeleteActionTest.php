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

class DeleteActionTest extends AbstractTest
{
    private $method = 'DELETE';
    private $uri = '/cards/';

    protected function getFixtures() : array
    {
        return [
            UserFixtures::class,
            DeckFixture::class,
            CardFixture::class
        ];
    }

    protected function setUp() : void
    {
        parent::setUp();
    }

    public function testPutDeckValid() : void
    {
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $card = self::getEntityManager()->getRepository(Card::class)->getAll($user)[0];

        $client = $this->makeRequest($this->method, $this->uri.$card->getId(), []);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertResponseOk($response);
        static::assertArrayHasKey('success', $content);
    }

    public function testPutDeckInvalidKey() : void
    {
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::STRANGER_EMAIL]);
        $card = self::getEntityManager()->getRepository(Card::class)->getAll($user)[0];

        $client = $this->makeRequest($this->method, $this->uri.$card->getId(), []);

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
