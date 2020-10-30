<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Change\Role;

use App\DataFixtures\Helper\UserFixtures;
use App\Domain\User\Entity\Types\Role;
use App\Tests\AbstractTest;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\RawMessage;

class RequestActionTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/user/change/role/';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid(): void
    {
        $this->makeRequestWithAuth([], '/user/current/', 'GET');
        $id = $this->content['id'];

        $this->makeRequestWithAuth([ 'id' => $id, 'role' => Role::USER ]);

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('success', $this->content);
    }

    public function testNotValidData() : void
    {
        $this->makeRequestWithAuth([ 'id' => '1asdfsadf', 'role' => '123']);
        $this->assertResponseCode(JsonResponse::HTTP_NOT_FOUND, $this->response);

        self::assertResponseCode(Response::HTTP_NOT_FOUND, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('role', $this->content['errors']);
        self::assertArrayHasKey('id', $this->content['errors']);
    }
}
