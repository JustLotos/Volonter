<?php

namespace App\Domain\Helper\Comment\UseCase\Create;

use App\Domain\Helper\Comment\Entity\Comment;
use App\Domain\Helper\Comment\Entity\Types\Id as CommentId;
use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Comment\CommentRepository;
use App\Domain\Helper\Task\Entity\Types\Id as TaskId;
use App\Domain\Helper\Task\TaskRepository;
use App\Domain\Helper\Volunteer\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use App\Domain\Helper\Volunteer\VolunteerRepository;
use App\Domain\User\Entity\User;
use App\Service\FlushService;
use DateTimeImmutable;

class Handler
{
    private $volunteerRepository;
    private $flushService;
    private $taskRepository;
    private $commentRepository;

    public function __construct(
        VolunteerRepository $volunteerRepository,
        CommentRepository $commentRepository,
        TaskRepository $taskRepository,
        FlushService $flushService
    ){
        $this->volunteerRepository = $volunteerRepository;
        $this->flushService = $flushService;
        $this->taskRepository = $taskRepository;
        $this->commentRepository = $commentRepository;
    }

    public function handle(User $user,Command $command): Comment {
        /** @var Volunteer $volunteer */
        $volunteer = $this->volunteerRepository->getById(new Id($user->getId()));

        /** @var Task $task */
        $task = $this->taskRepository->getById(new TaskId($command->taskId));

        $comment = new Comment($volunteer, CommentId::next(),  $task, $command->text, new DateTimeImmutable('now'));
        $this->commentRepository->add($comment);
        $this->flushService->flush();

        return $comment;
    }
}