<?php

declare(strict_types=1);

namespace App\Tests\DeckController;

use App\DataFixtures\DeckFixture;
use App\DataFixtures\UserFixtures;
use App\Entity\Deck;
use App\Entity\User;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class CGetActionTest extends AbstractTest
{
    private $clientAuth;

    public function getFixtures() : array
    {
        return [
            UserFixtures::class,
            DeckFixture::class
        ];
    }

    public function setUp() : void
    {
        parent::setUp();
        $this->clientAuth = $this->createAuthenticatedClient();
        $this->url .= '/decks';
    }

    public function testCGetDeckValid() : void
    {
        $this->clientAuth->request('GET', $this->url, [], [], [], '');

        /** @var Response $response */
        $response = $this->clientAuth->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertResponseOk($response);
        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('description', $content[0]);
    }

    public function testStrangeCGetDeckValid() : void
    {

        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::STRANGER_EMAIL]);
        $deckStranger = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['user' => $user]);

        $this->clientAuth->request('GET', $this->url, [], [], [], '');

        /** @var Response $response */
        $response = $this->clientAuth->getResponse();
        $content = json_decode($response->getContent(), true);

        $check = false;
        foreach ($content as $deck) {
            if($deck['id'] === $deckStranger->getId()) {
                $check = true;
            }
        }
        $this->assertFalse($check);
    }
}
