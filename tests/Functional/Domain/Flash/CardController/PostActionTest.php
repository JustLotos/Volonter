<?php

declare(strict_types=1);

namespace App\Tests\CardController;

use App\DataFixtures\CardFixture;
use App\DataFixtures\DeckFixture;
use App\DataFixtures\UserFixtures;
use DateTime;
use App\Entity\Deck;
use App\Entity\User;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class PostActionTest extends AbstractTest
{
    private $method = 'POST';
    private $uri = '/cards';

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

    public function testPostDeckValid() : void
    {
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['user'=>$user]);

        $nextRepeatAt = new DateTime('+5 minutes');
        $client = $this->makeRequest($this->method, $this->uri, [
            'deck' => $deck->getId(),
            'name' => 'deckName',
            'next_repeat_at' => $nextRepeatAt->format(DATE_ATOM),
            'front_records' => [
                ['content'=> ['test']]
            ],
            'back_records' => [
                ['content' => ['test']]
            ]
        ]);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertResponseCode(Response::HTTP_CREATED, $response);
        static::assertArrayHasKey('id', $content);
        static::assertArrayHasKey('name', $content);
        static::assertArrayHasKey('first_repeat_at', $content);
        static::assertArrayHasKey('next_repeat_at', $content);
        static::assertArrayHasKey('created_at', $content);
        static::assertArrayHasKey('updated_at', $content);
        static::assertArrayHasKey('count_repeat', $content);
        static::assertArrayHasKey('records', $content);
        static::assertArrayHasKey('content', $content['records'][0]);
        static::assertArrayHasKey('id', $content['records'][0]);
        static::assertArrayHasKey('side', $content['records'][0]);
    }

    public function testPostDeckInvalidKey() : void
    {
        $client = $this->makeRequest($this->method, $this->uri, ['invalid'=>'invalid']);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $response);
        static::assertArrayHasKey('errors', $content);
        $content = $content['errors'];

        static::assertArrayHasKey('name', $content);
        static::assertArrayHasKey('next_repeat_at', $content);
        static::assertArrayHasKey('front_records', $content);
        static::assertArrayHasKey('back_records', $content);

        $nextRepeatAt = new DateTime('+5 minutes');
        $client = $this->makeRequest($this->method, $this->uri, [
            'name' => 'deckName',
            'next_repeat_at' => $nextRepeatAt->format(DATE_ATOM),
            'front_records' => [
                []
            ],
            'back_records' => [
                []
            ]
        ]);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $response);
        static::assertArrayHasKey('errors', $content);
        $content = $content['errors'];
        static::assertArrayHasKey('front_records[0].content', $content);
        static::assertArrayHasKey('back_records[0].content', $content);
    }
}
