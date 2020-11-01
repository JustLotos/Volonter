<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Comment\CrudController;

use App\DataFixtures\Helper\TaskFixtures;
use App\Domain\Helper\Comment\Entity\Comment;
use App\Tests\AbstractTest;

class GetTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/task/get/';

    public function getFixtures() : array
    {
        return [TaskFixtures::class];
    }

    public function testBaseFetch() : void
    {
        $this->makeRequestWithAuth([], '/task/cget/', 'GET');
        /** @var Comment $task */
        $task = $this->content[0];
        $this->makeRequestWithAuth();

        $this->makeRequestWithAuth([], $this->uri.$task['id'].'/');

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content);
        self::assertArrayHasKey('title', $this->content);
    }
}
