<?php

declare(strict_types=1);

namespace App\Tests\CardController;

use App\DataFixtures\CardFixture;
use App\DataFixtures\DeckFixture;
use App\DataFixtures\UserFixtures;
use App\Entity\Record;
use DateTime;
use App\Entity\Card;
use App\Entity\Deck;
use App\Entity\User;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class PutActionTest extends AbstractTest
{
    private $method = 'PUT';
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

    public function testPostValid() : void
    {
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['user'=>$user]);
        /** @var Card $card */
        $card = self::getEntityManager()->getRepository(Card::class)->findOneBy(['deck'=>$deck]);

        $front = false;
        $back = false;
        foreach ($card->getRecords() as $record) {
            if ($record->getSide() == Record::FRONTSIDE) {
                $front = $record->getId();
            }
            if ($record->getSide() == Record::BACKSIDE) {
                $back = $record->getId();
            }
        }

        $nextRepeatAt = new DateTime('+5 minutes');
        $client = $this->makeRequest($this->method, $this->uri.$card->getId(), [
            'deck' => $deck->getId(),
            'name' => 'deckName',
            'count_repeat'=>1,
            'next_repeat_at' => $nextRepeatAt->format(DATE_ATOM),
            'front_records' => [
                [
                    'id' => $front,
                    'content'=> ['test']
                ]
            ],
            'back_records' => [
                [
                    'id' => $back,
                    'content' => ['test']
                ]
            ]
        ]);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertResponseOk($response);
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
}
