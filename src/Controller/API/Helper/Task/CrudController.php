<?php

declare(strict_types=1);

namespace App\Controller\API\Helper\Task;

use App\Controller\ControllerHelper;
use App\Domain\Helper\Task\Entity\Task;
use App\Domain\Helper\Volunteer\Entity\Types\Id;
use App\Domain\Helper\Task\TaskRepository;
use App\Domain\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
