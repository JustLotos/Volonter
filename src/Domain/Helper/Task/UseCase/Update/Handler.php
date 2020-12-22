<?php

namespace App\Domain\Helper\Task\UseCase\Update;

use App\Domain\Helper\Tag\TagRepository;
use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Task\Entity\Types\Geo;
use App\Domain\Helper\Task\TaskRepository;
use App\Domain\Helper\Task\TaskTrait;
use App\Service\FlushService;

class Handler
{
    use TaskTrait;

    private $flushService;
    private $tagRepository;
    private $taskRepository;

    public function __construct(
        FlushService $flushService,
        TagRepository $tagRepository,
        TaskRepository $taskRepository
    ) {
        $this->flushService = $flushService;
        $this->tagRepository = $tagRepository;
        $this->taskRepository = $taskRepository;
    }

    public function handle(Task $task, Command $command): Task
    {
        $command->geo = new Geo($command->geo['x'] ?: 0, $command->geo['y'] ?: 0);
        $task->update($command);
        $this->removeTags($task, $command);
        $this->addTags($task, $command, $this->tagRepository);
        $this->flushService->flush();
        return $task;
    }
}
