<?php

declare(strict_types=1);

namespace App\Controller\API\User\Register;

use App\Controller\ControllerHelper;
use App\Domain\User\Entity\User;
use App\Domain\User\UseCase\Register\ByEmail\Confirm\Command as ConfirmCommand;
use App\Domain\User\UseCase\Register\ByEmail\Confirm\Handler as ConfirmHandler;
use App\Domain\User\UseCase\Register\ByEmail\Request\Command as RegisterPayloads;
use App\Domain\User\UseCase\Register\ByEmail\Request\Handler as RegisterHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/user/register/email") */
class RegisterByEmailController extends AbstractController
{
    use ControllerHelper;
    /** @Route("/request/", name="registerByEmail", methods={"POST"}, options={"no_auth": true}) */
    public function register(
        Request $request,
        RegisterHandler $handler,
        AuthenticationSuccessHandler $ash
    ) {
        /** @var RegisterPayloads $command */
        $command = $this->serializer->deserialize($request, RegisterPayloads::class);
        $user = $handler->handle($command);
        return $ash->handleAuthenticationSuccess($user);
    }

    /** @Route("/confirm/{token}/", name="registerByEmailConfirm", methods={"GET"}, options={"no_auth": true}) */
    public function confirm(ConfirmHandler $handler, string $token): RedirectResponse
    {
        $handler->handle(new ConfirmCommand($token));
        return $this->redirectToRoute('index', [
            'vueRouting' => '',
            'registerByEmail' => 'confirm'
        ]);
    }
}
