<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Comment\CrudController;

use App\DataFixtures\Helper\TaskFixtures;
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
