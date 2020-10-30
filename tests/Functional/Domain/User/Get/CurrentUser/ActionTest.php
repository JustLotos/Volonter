<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Get\CurrentUser;

use App\DataFixtures\Helper\UserFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class ActionTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/user/current/';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid() : void
    {
        $this->makeRequestWithAuth();

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content);
        self::assertArrayHasKey('email', $this->content);
        self::assertArrayHasKey('role', $this->content);
        self::assertArrayHasKey('status', $this->content);
        self::assertArrayHasKey('createdAt', $this->content);
        self::assertArrayHasKey('updatedAt', $this->content);
    }
}
