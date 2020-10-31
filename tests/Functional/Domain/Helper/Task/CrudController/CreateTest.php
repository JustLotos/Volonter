<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Task\CrudController;

use App\DataFixtures\Helper\TaskFixtures;
use App\DataFixtures\User\UserFixtures;
use App\Tests\AbstractTest;

class CreateTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/task/new/';

    public function getFixtures() : array
    {
        return [TaskFixtures::class];
    }

    public function testCreateValid() : void
    {
        $this->makeRequestWithAuth([ 'title' => 'test' ]);

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content);
        self::assertArrayHasKey('title', $this->content);
    }
}
