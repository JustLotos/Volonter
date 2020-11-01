<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Comment\CrudController;

use App\DataFixtures\Helper\CommentFixtures;
use App\Tests\AbstractTest;

class CgetTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/comment/cget/';

    public function getFixtures() : array
    {
        return [CommentFixtures::class];
    }

    public function testBaseFetch() : void
    {
        $this->makeRequestWithAuth([], '/task/cget/', 'GET');
        $task = $this->content[0];

        $this->makeRequestWithAuth([], $this->uri.$task['id'].'/');

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content[0]);
        self::assertArrayHasKey('text', $this->content[0]);
    }
}
