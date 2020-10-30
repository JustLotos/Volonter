<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Task\CrudController;

use App\DataFixtures\Helper\TaskFixtures;
use App\DataFixtures\User\UserFixtures;
use App\Tests\AbstractTest;

class CgetTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/task/cget/';

    public function getFixtures() : array
    {
        return [TaskFixtures::class];
    }

    public function testBaseFetch() : void
    {
        $this->makeRequestWithAuth();

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content[0]);
        self::assertArrayHasKey('title', $this->content[0]);
        self::assertEquals(TaskFixtures::ADMIN_TASK_COUNT, count($this->content));
    }
}
