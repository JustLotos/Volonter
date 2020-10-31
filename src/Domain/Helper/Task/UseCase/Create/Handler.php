<?php

namespace App\Domain\Helper\Task\UseCase\Create;

use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Task\TaskRepository;
use App\Domain\Helper\Volunteer\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use App\Domain\Helper\Volunteer\VolunteerRepository;
use App\Domain\User\Entity\User;
use App\Service\FlushService;

class Handler
{
    private $volunteerRepository;
    private $flushService;
    private $taskRepository;

    public function __construct(
        VolunteerRepository $volunteerRepository,
        TaskRepository $taskRepository,
        FlushService $flushService
    ){
        $this->volunteerRepository = $volunteerRepository;
        $this->flushService = $flushService;
        $this->taskRepository = $taskRepository;
    }

    public function handle(User $user,Command $command): Task {

        /** @var Volunteer $volunteer */
        $volunteer = $this->volunteerRepository->getById(new Id($user->getId()));
        $task = new Task($volunteer, Id::next(), $command->title);
        $this->taskRepository->add($task);
        $this->flushService->flush();

        return $task;
    }
}