<?php

namespace App\Domain\Helper\Task\UseCase\Update;

use App\Domain\Helper\Tag\TagRepository;
use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Task\TaskRepository;
use App\Domain\Helper\Task\TaskTrait;
use App\Service\FlushService;

class Handler
{
    use TaskTrait;

    private $flushService;
    private $tagRepository;
    /**
     * @var TaskRepository
     */
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
        $task->update($command);
        $this->removeTags($task, $command);
        $this->addTags($task, $command, $this->tagRepository);

        $this->flushService->flush();

        var_dump($this->taskRepository->findOneBy(['id'=>$task->getId()])->getTags());
        return $task;
    }
}
