<?php

namespace App\Domain\Helper\Task\UseCase\Remove;

use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Task\TaskRepository;
use App\Service\FlushService;

class Handler
{
    private $flushService;
    private $taskRepository;

    public function __construct(
        TaskRepository $taskRepository,
        FlushService $flushService
    ){
        $this->flushService = $flushService;
        $this->taskRepository = $taskRepository;
    }

    public function handle(Task $task): Task {
        $this->taskRepository->remove($task);
        $this->flushService->flush();
        return $task;
    }
}