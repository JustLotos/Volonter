<?php

namespace App\Domain\Helper\Comment\UseCase\Update;

use App\Domain\Helper\Comment\CommentRepository;
use App\Domain\Helper\Volunteer\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use App\Domain\Helper\Volunteer\VolunteerRepository;
use App\Domain\User\Entity\User;
use App\Service\FlushService;

class Handler
{
    private $flushService;

    public function __construct(FlushService $flushService)
    {
        $this->flushService = $flushService;
    }

    public function handle(Task $task, Command $command): Task {
        $task->update($command);
        $this->flushService->flush();
        return $task;
    }
}