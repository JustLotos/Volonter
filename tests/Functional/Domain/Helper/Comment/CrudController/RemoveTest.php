<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Comment\CrudController;

use App\DataFixtures\Helper\TaskFixtures;
use App\DataFixtures\User\UserFixtures;
use App\Domain\Helper\Comment\Entity\Comment;
use App\Tests\AbstractTest;
use App\Tests\APITestCase;
use Doctrine\ORM\EntityManager;

class RemoveTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/task/remove/';


    public function getFixtures() : array
    {
        return [TaskFixtures::class];
    }

    public function testRemove() : void
    {
        $this->makeRequestWithAuth([], '/task/cget/', 'GET');

        /** @var Comment $task */
        $task = $this->content[0];

        $this->makeRequestWithAuth([], $this->uri.$task['id'].'/');
        $this->assertResponseOk($this->response);
    }
}
