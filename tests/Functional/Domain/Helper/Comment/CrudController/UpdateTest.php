<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Comment\CrudController;

use App\DataFixtures\Helper\CommentFixtures;
use App\Domain\Helper\Comment\Entity\Comment;
use App\Tests\AbstractTest;

class UpdateTest extends AbstractTest
{
    protected $method = 'PUT';
    protected $uri = '/comment/update/';

    public function getFixtures() : array
    {
        return [CommentFixtures::class];
    }

    public function testBaseFetch() : void
    {
        $this->makeRequestWithAuth([], '/task/cget/', 'GET');

        /** @var Comment $task */
        $task = $this->content[0];
        $this->makeRequestWithAuth([], '/comment/cget/'.$task['id'].'/', 'GET');

        $comment = $this->content[0];
        $this->makeRequestWithAuth(['text'=>'test'], $this->uri.$comment['id'].'/');


        $this->assertResponseOk($this->response);
    }
}
