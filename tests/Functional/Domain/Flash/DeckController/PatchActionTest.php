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

class PatchActionTest extends AbstractTest
{
    private $method = 'PATCH';
    private $uri = '/decks/';
    private $deck;

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
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $this->deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['user' => $user]);
    }

    public function testPatchDeckValid() : void
    {
        $client = $this->makeRequest($this->method, $this->uri.$this->deck->getId(), [
            'name' => 'deckName',
            'description' => 'description',
            'limit_repeat' => 20,
            'limit_learning' => 20,
            'difficulty_index' => 1,
            'base_index' => 1,
            'modifier_index' => 1
        ]);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertResponseOk($response);
        static::assertArrayHasKey('id', $content);
        static::assertArrayHasKey('name', $content);
        static::assertArrayHasKey('description', $content);
        static::assertArrayHasKey('limit_repeat', $content);
        static::assertArrayHasKey('limit_learning', $content);
        static::assertArrayHasKey('difficulty_index', $content);
        static::assertArrayHasKey('base_index', $content);
        static::assertArrayHasKey('modifier_index', $content);
        static::assertArrayHasKey('created_at', $content);
        static::assertArrayHasKey('updated_at', $content);
    }

    public function testPatchDeckNotFound() : void
    {
        $client = $this->makeRequest($this->method, $this->uri.'invalid', [
            'test' => 'test',
        ]);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertResponseNotFound($response);
        static::assertArrayHasKey('errors', $content);
        static::assertArrayHasKey('message', $content['errors']);
    }

    public function testPatchDeckInvalidValue() : void
    {
        $client = $this->makeRequest($this->method, $this->uri.$this->deck->getId(), [
            'name' => '',
            'limit_repeat' => '',
            'limit_learning' => '',
            'difficulty_index' => '',
            'base_index' => '',
            'modifier_index' => '',
        ]);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $response);
        static::assertArrayHasKey('errors', $content);
        $content = $content['errors'];
        static::assertArrayHasKey('name', $content);
        static::assertArrayHasKey('limit_repeat', $content);
        static::assertArrayHasKey('limit_learning', $content);
        static::assertArrayHasKey('difficulty_index', $content);
        static::assertArrayHasKey('base_index', $content);
        static::assertArrayHasKey('modifier_index', $content);
    }
}
