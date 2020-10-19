<?php

declare(strict_types=1);

namespace App\Tests\DeckController;

use App\DataFixtures\CardFixture;
use App\DataFixtures\DeckFixture;
use App\DataFixtures\UserFixtures;
use App\Entity\Deck;
use App\Entity\User;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class GetActionTest extends AbstractTest
{
    private $clientAuth;

    protected function getFixtures() : array
    {
        parent::getFixtures();
        return [
            UserFixtures::class,
            DeckFixture::class,
            CardFixture::class
        ];
    }

    protected function setUp() : void
    {
        parent::setUp();
        $this->clientAuth = $this->createAuthenticatedClient();
        $this->url .= '/decks/';
    }

    public function testGetDeckValid() : void
    {
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $deckOwner = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['user' => $user]);

        $this->clientAuth->request(
            'GET',
            $this->url.$deckOwner->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ''
        );

        /** @var Response $response */
        $response = $this->clientAuth->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertResponseOk($response);
        static::assertArrayHasKey('id', $content);
        static::assertArrayHasKey('name', $content);
        static::assertArrayHasKey('description', $content);
        static::assertArrayHasKey('cards', $content);
    }

    public function testGetDeckStrangerAccess() : void
    {
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::STRANGER_EMAIL]);
        $deckStranger = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['user' => $user]);
        $this->clientAuth->request(
            'GET',
            $this->url.$deckStranger->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ''
        );

        /** @var Response $response */
        $response = $this->clientAuth->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertResponseCode(Response::HTTP_NOT_FOUND, $response);
        static::assertArrayHasKey('errors', $content);
        static::assertArrayHasKey('message', $content['errors']);
    }

    public function testGetDeckNotFound() : void
    {
        $this->clientAuth->request(
            'GET',
            $this->url."13213231",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ''
        );

        /** @var Response $response */
        $response = $this->clientAuth->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertResponseCode(Response::HTTP_NOT_FOUND, $response);
        static::assertArrayHasKey('errors', $content);
        static::assertArrayHasKey('message', $content['errors']);
    }
}
