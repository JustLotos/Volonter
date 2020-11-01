<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Comment\CrudController;

use App\DataFixtures\Helper\TaskFixtures;
use App\Tests\AbstractTest;

class CreateTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/comment/new/';

    public function getFixtures() : array
    {
        return [TaskFixtures::class];
    }

    public function testCreateValid() : void
    {
        $this->makeRequestWithAuth([], '/task/cget/', 'GET');
        $task = $this->content[0];

        $this->makeRequestWithAuth([
            'text' => 'test',
            'taskId' => $task['id']
        ]);

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content);
        self::assertArrayHasKey('text', $this->content);
    }
}
