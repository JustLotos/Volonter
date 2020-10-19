<?php

declare(strict_types=1);

namespace App\Controller\API\User\Change;

use App\Controller\ControllerHelper;
use App\Domain\User\UseCase\Change\Role\Handler;
use App\Domain\User\UseCase\Change\Role\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/user/change/role") */
class ChangeRoleController extends AbstractController
{
    use ControllerHelper;

    /** @Route("/", name="changeRole", methods={"POST"}) */
    public function reset(Request $request, Handler $handler): Response
    {
        /** @var Command $command */
        $command = $this->serializer->deserialize($request, Command::class);
        $handler->handle($command);
        return $this->response($this->getSimpleSuccessResponse());
    }
}
