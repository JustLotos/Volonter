<?php

declare(strict_types=1);

namespace App\Tests\DeckController;

use App\DataFixtures\CardFixture;
use App\DataFixtures\DeckFixture;
use App\DataFixtures\UserFixtures;

use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class PostActionTest extends AbstractTest
{
    private $method = 'POST';
    private $uri = '/decks';

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

    public function testPostDeckValid() : void
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

        $this->assertResponseCode(Response::HTTP_CREATED, $response);
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

    public function testPostDeckInvalidKey() : void
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
}
