<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Helper\Task\CrudController;

use App\DataFixtures\Helper\TaskFixtures;
use App\DataFixtures\User\UserFixtures;
use App\Domain\Helper\Task\Entity\Task;
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

        /** @var Task $task */
        $task = $this->content[0];

        $this->makeRequestWithAuth([], $this->uri.$task['id'].'/');
        $this->assertResponseOk($this->response);
    }
}
