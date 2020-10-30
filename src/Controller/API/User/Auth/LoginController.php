<?php

declare(strict_types=1);

namespace App\Controller\API\User\Auth;

use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/auth/login") */
class LoginController extends AbstractController
{
    /** @Route("/", name="login", methods={"POST"}, options={"no_auth": true}) */
    public function login() : void
    {
        throw new DomainException('You shouldn\'t have gotten to this place');
    }
}
