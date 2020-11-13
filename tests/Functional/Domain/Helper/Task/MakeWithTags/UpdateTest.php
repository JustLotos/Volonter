<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Task\MakeWithTags;

use App\DataFixtures\Helper\TagFixtures;
use App\DataFixtures\Helper\TaskFixtures;
use App\Domain\Helper\Task\Entity\Task;
use App\Tests\AbstractTest;

class UpdateTest extends AbstractTest
{
    protected $method = 'PUT';
    protected $uri = '/task/update/';

    public function getFixtures() : array
    {
        return [TagFixtures::class];
    }

    public function testEmptyTags() : void
    {
        $this->makeRequestWithAuth([
            'title' => 'test',
            'body' => 'test',
            'tags' => []
        ]);

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content);
        self::assertArrayHasKey('title', $this->content);
    }

    public function testWithNewTags() : void
    {

        $this->makeRequestWithAuth([], '/task/cget/?tags=Y', 'GET');
        /** @var Task $task */
        $task = $this->content[0];
        var_dump($task['tags']);
        $this->makeRequestWithAuth([
            'title' => 'test',
            'body' => 'test',
            'tags' => [
                ['name' => 'tag0'],
                ['name' => 'tag2'],
                ['name' => 'test'],
            ]
        ], $this->uri.$task['id'].'/');


        self::assertArrayHasKey('id', $this->content);
        self::assertArrayHasKey('title', $this->content);
        self::assertArrayHasKey('tags', $this->content);
        self::assertArrayHasKey('name', $this->content['tags'][0]);
        self::assertArrayHasKey('id', $this->content['tags'][0]);
    }
}
