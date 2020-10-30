<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Volunteer\FetchController;


use App\DataFixtures\Helper\VolunteerFixtures;
use App\DataFixtures\User\UserFixtures;
use App\Tests\AbstractTest;

class GetCurrentVolunteerTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/volunteer/current/';

    public function getFixtures() : array
    {
        return [
            UserFixtures::class,
            VolunteerFixtures::class
        ];
    }

    public function testBaseFetch() : void
    {
        $this->makeRequestWithAuth();

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content);
        self::assertArrayHasKey('email', $this->content);
        self::assertArrayHasKey('role', $this->content);
        self::assertArrayHasKey('status', $this->content);
        self::assertArrayHasKey('createdAt', $this->content);
        self::assertArrayHasKey('updatedAt', $this->content);
        self::assertArrayHasKey('name', $this->content);
        self::assertArrayHasKey('first', $this->content['name']);
        self::assertArrayHasKey('last', $this->content['name']);
    }
}
