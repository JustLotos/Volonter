<?php

declare(strict_types=1);

namespace App\Controller\API\User\Change;

use App\Controller\ControllerHelper;
use App\Domain\User\UseCase\Change\Email\Confirm\Command as ConfirmCommand;
use App\Domain\User\UseCase\Change\Email\Confirm\Handler as ConfirmHandler;
use App\Domain\User\UseCase\Change\Email\Request\Command as RequestCommand;
use App\Domain\User\UseCase\Change\Email\Request\Handler as RequestHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/user/change/email") */
class ChangeEmailController extends AbstractController
{
    use ControllerHelper;

    /** @Route("/request/", name="changeEmail", methods={"POST"}) */
    public function reset(Request $request, RequestHandler $handler): Response
    {
        /** @var RequestCommand $command */
        $command = $this->serializer->deserialize($request, RequestCommand::class);
        $handler->handle($command);
        return $this->response($this->getSimpleSuccessResponse());
    }

    /** @Route("/confirm/{token}", name="changeEmailConfirm", methods={"GET"}) */
    public function confirm(ConfirmHandler $handler, string $token): RedirectResponse
    {
        $handler->handle(new ConfirmCommand($token));
        return $this->redirectToRoute('index', [
            'vueRouting' => '',
            'changeEmail' => 'confirm'
        ]);
    }
}
