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

class PutActionTest extends AbstractTest
{
    private $method = 'PUT';
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
        $this->uri .= $this->deck->getId();
    }

    public function testPutDeckValid() : void
    {
        $client = $this->makeRequest($this->method, $this->uri, [
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
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('description', $content);
        $this->assertArrayHasKey('limit_repeat', $content);
        $this->assertArrayHasKey('limit_learning', $content);
        $this->assertArrayHasKey('difficulty_index', $content);
        $this->assertArrayHasKey('base_index', $content);
        $this->assertArrayHasKey('modifier_index', $content);
        $this->assertArrayHasKey('created_at', $content);
        $this->assertArrayHasKey('updated_at', $content);
    }

    public function testPutDeckInvalidKey() : void
    {
        $client = $this->makeRequest($this->method, $this->uri, [
            'invalid' => 'invalid',
        ]);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $response);
        $this->assertArrayHasKey('errors', $content);
        $content = $content['errors'];
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('limit_repeat', $content);
        $this->assertArrayHasKey('limit_learning', $content);
        $this->assertArrayHasKey('difficulty_index', $content);
        $this->assertArrayHasKey('base_index', $content);
        $this->assertArrayHasKey('modifier_index', $content);
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
        $this->assertArrayHasKey('errors', $content);
        $this->assertArrayHasKey('message', $content['errors']);

    }
}
