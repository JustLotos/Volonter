<?php

declare(strict_types=1);

namespace App\Controller\API\User\Get;

use App\Controller\ControllerHelper;
use App\Domain\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/user/current") */
class CurrentUserController extends AbstractController
{
    use ControllerHelper;
    /** @Route("/", name="getCurrentUser", methods={"GET"}) */
    public function getCurrent() : Response
    {
        return $this->response($this->serializer->serialize($this->getUser(), [User::GROUP_SIMPLE, User::GROUP_DETAIL]));
    }
}
