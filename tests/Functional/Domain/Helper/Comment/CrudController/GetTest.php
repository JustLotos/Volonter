<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Comment\CrudController;

use App\DataFixtures\Helper\CommentFixtures;
use App\Domain\Helper\Comment\Entity\Comment;
use App\Tests\AbstractTest;

class GetTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/comment/get/';

    public function getFixtures() : array
    {
        return [CommentFixtures::class];
    }

    public function testBaseFetch() : void
    {
        $this->makeRequestWithAuth([], '/task/cget/');
        $task = $this->content[0];

        $this->makeRequestWithAuth([],  '/comment/cget/'.$task['id'].'/');
        /** @var Comment $task */

        $comment = $this->content[0];
        $this->makeRequestWithAuth([], $this->uri.$comment['id'].'/');

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content);
        self::assertArrayHasKey('text', $this->content);
    }
}
