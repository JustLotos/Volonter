<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Task\MakeWithTags;

use App\DataFixtures\Helper\TagFixtures;
use App\DataFixtures\Helper\TaskFixtures;
use App\Tests\AbstractTest;

class CreateTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/task/new/?tags=Y';

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
        $this->makeRequestWithAuth([
            'title' => 'test',
            'body' => 'test',
            'tags' => [
                ['name' => 'tag0'],
                ['name' => 'tag2'],
                ['name' => 'test'],
            ]
        ]);

        self::assertArrayHasKey('id', $this->content);
        self::assertArrayHasKey('title', $this->content);
        self::assertArrayHasKey('tags', $this->content);
        self::assertArrayHasKey('name', $this->content['tags'][0]);
        self::assertArrayHasKey('id', $this->content['tags'][0]);

        var_dump($this->content);
    }
}
