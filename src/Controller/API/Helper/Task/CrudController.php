<?php

declare(strict_types=1);

namespace App\Controller\API\Helper\Task;

use App\Controller\ControllerHelper;
use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Task\UseCase\Create\Command as CreateCommand;
use App\Domain\Helper\Task\UseCase\Create\Handler as CreateHandler;
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

        return  $this->response($this->serializer->serialize($tasks));
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
}
