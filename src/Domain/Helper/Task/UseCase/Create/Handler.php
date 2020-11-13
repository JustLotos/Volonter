<?php

namespace App\Domain\Helper\Task\UseCase\Create;

use App\Domain\Helper\Task\Entity\Types\Id;
use App\Domain\Helper\Tag\TagRepository;
use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Task\TaskRepository;
use App\Domain\Helper\Task\TaskTrait;
use App\Domain\Helper\Volunteer\Entity\Types\Id as VolID;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use App\Domain\Helper\Volunteer\VolunteerRepository;
use App\Domain\User\Entity\User;
use App\Service\FlushService;
use DateTimeImmutable;

class Handler
{
    use TaskTrait;

    private $volunteerRepository;
    private $flushService;
    private $taskRepository;
    private $tagRepository;

    public function __construct(
        VolunteerRepository $volunteerRepository,
        TaskRepository $taskRepository,
        TagRepository $tagRepository,
        FlushService $flushService
    ) {
        $this->volunteerRepository = $volunteerRepository;
        $this->flushService = $flushService;
        $this->taskRepository = $taskRepository;
        $this->tagRepository = $tagRepository;
    }

    public function handle(User $user, Command $command): Task
    {
        /** @var Volunteer $volunteer */
        $volunteer = $this->volunteerRepository->getById(new VolID($user->getId()));
        $task = new Task($volunteer, Id::next(), $command, new DateTimeImmutable());

        $this->addTags($task, $command, $this->tagRepository);

        $this->taskRepository->add($task);
        $this->flushService->flush();

        return $task;
    }


}
