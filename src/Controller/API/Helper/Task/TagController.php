<?php

declare(strict_types=1);

namespace App\Controller\API\Helper\Task;

use App\Controller\ControllerHelper;
use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Task\UseCase\AddTag\Command;
use App\Domain\Helper\Task\UseCase\AddTag\Handler;
use App\Domain\Helper\Task\UseCase\RemoveTag\Command as CommandRemoveTag;
use App\Domain\Helper\Task\UseCase\RemoveTag\Handler as HandlerRemoveTag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/task") */
class TagController extends AbstractController
{
    use ControllerHelper;

    /** @Route("/addTag/{id}", name="addTag", methods={"POST"}) */
    public function addTag(Request $request, Handler $handler, Task $task) : Response
    {
        /** @var Command $command */
        $command = $this->serializer->serialize($request, Command::class);
        $task = $handler->handle($command, $task);
        return  $this->response($this->serializer->serialize($task));
    }

    /** @Route("/removeTag/{id}/", name="removeTag", methods={"POST"}) */
    public function getTask(Request $request, HandlerRemoveTag $handler, Task $task) : Response
    {
        /** @var CommandRemoveTag $command */
        $command = $this->serializer->serialize($request, CommandRemoveTag::class);
        $task = $handler->handle($command, $task);
        return  $this->response($this->serializer->serialize($task, Task::GROUP_SIMPLE));
    }
}
