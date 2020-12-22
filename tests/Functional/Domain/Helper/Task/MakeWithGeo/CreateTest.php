<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Task\MakeWithGeo;

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
        $this->makeRequestWithAuth([
            'title' => 'test',
            'body' => 'test',
            'geo' => [
                'x' => '50',
                'y' => '100'
            ]
        ]);

        var_dump($this->response);

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content);
        self::assertArrayHasKey('title', $this->content);
    }
}
