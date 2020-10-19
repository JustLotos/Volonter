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

class CGetActionTest extends AbstractTest
{
    private $method = 'GET';
    private $uri = '/cards';

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

    public function testCGetDeckValid() : void
    {
        $client = $this->makeRequest($this->method, $this->uri, []);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertResponseOk($response);
        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
    }

    public function testStrangeCGetDeckValid() : void
    {

        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::STRANGER_EMAIL]);
        $strangerCards = self::getEntityManager()->getRepository(Card::class)->getAll($user);

        $client = $this->makeRequest($this->method, $this->uri, []);

        /** @var Response $response */
        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $check = false;
        foreach ($content as $card) {
            foreach ($strangerCards as $strangerCard) {
                if($card['id'] === $strangerCard->getId()) {
                    $check = true;
                }
            }
        }

        $this->assertFalse($check);
    }
}
