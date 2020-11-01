<?php

declare(strict_types=1);

namespace App\Controller\API\Helper\Task;

use App\Controller\ControllerHelper;
use App\Domain\Helper\Security\TaskVoter;
use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Task\UseCase\Create\Command as CreateCommand;
use App\Domain\Helper\Task\UseCase\Create\Handler as CreateHandler;
use App\Domain\Helper\Task\UseCase\Remove\Handler as RemoveHandler;
use App\Domain\Helper\Task\UseCase\Update\Command as UpdateCommand;
use App\Domain\Helper\Task\UseCase\Update\Handler as UpdateHandler;
use App\Domain\Helper\Volunteer\Entity\Types\Id;
use App\Domain\Helper\Task\TaskRepository;
use App\Domain\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/task") */
class CrudController extends AbstractController
{
    use ControllerHelper;

    /** @Route("/cget/", name="getAllTasks", methods={"GET"}) */
    public function getAllTasks(TaskRepository $repository) : Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $tasks = $repository->getAll(new Id($user->getId()->getValue()));
        return  $this->response($this->serializer->serialize($tasks,  Task::GROUP_SIMPLE));
    }

    /** @Route("/get/{id}/", name="getTask", methods={"GET"}) */
    public function getTask(Task $task) : Response
    {
        $this->denyAccessUnlessGranted(TaskVoter::EDIT, $task, TaskVoter::NOT_FOUND_MESSAGE);
        return  $this->response($this->serializer->serialize($task, Task::GROUP_SIMPLE));
    }

    /** @Route("/new/", name="createNewTask", methods={"POST"}) */
    public function create(Request $request, CreateHandler $handler): Response {
        /** @var CreateCommand $command */
        $command = $this->serializer->deserialize($request, CreateCommand::class);
        $this->validator->validate($command);

        /** @var User $user */
        $user = $this->getUser();
        $task = $handler->handle($user, $command);
        return $this->response($this->serializer->serialize($task, Task::GROUP_SIMPLE));
    }

    /** @Route("/update/{id}/", name="updateTask", methods={"PUT"}) */
    public function update(Request $request, Task $task, UpdateHandler $handler): Response {
        $this->denyAccessUnlessGranted(TaskVoter::EDIT, $task, TaskVoter::NOT_FOUND_MESSAGE);
        /** @var UpdateCommand $command */
        $command = $this->serializer->deserialize($request, UpdateCommand::class);
        $this->validator->validate($command);
        $handler->handle($task, $command);
        return $this->response($this->getSimpleSuccessResponse());
    }

    /** @Route("/remove/{id}/", name="deleteTask", methods={"POST"}) */
    public function remove(Task $task, RemoveHandler $handler): Response {
        $this->denyAccessUnlessGranted(TaskVoter::EDIT, $task, TaskVoter::NOT_FOUND_MESSAGE);
        $handler->handle($task);
        return $this->response($this->getSimpleSuccessResponse());
    }
}
