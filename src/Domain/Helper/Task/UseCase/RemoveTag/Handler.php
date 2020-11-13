<?php

namespace App\Domain\Helper\Task\UseCase\RemoveTag;

use App\Domain\Helper\Tag\TagRepository;
use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Task\TaskRepository;
use App\Domain\Helper\Task\TaskTrait;
use App\Service\FlushService;

class Handler
{
    use TaskTrait;

    private $flushService;
    private $taskRepository;
    private $tagRepository;

    public function __construct(
        TaskRepository $taskRepository,
        TagRepository $tagRepository,
        FlushService $flushService
    ) {
        $this->flushService = $flushService;
        $this->taskRepository = $taskRepository;
        $this->tagRepository = $tagRepository;
    }

    public function handle(Command $command, Task $task): Task
    {
        $this->removeTags($task, $command);
        $this->taskRepository->add($task);
        $this->flushService->flush();
        return $task;
    }
}
