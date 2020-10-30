<?php

declare(strict_types=1);

namespace App\Controller\API\Helper\Volunteer;

use App\Controller\ControllerHelper;
use App\Domain\Helper\Volunteer\Entity\Types\Id;
use App\Domain\Helper\Volunteer\Entity\Volunteer;
use App\Domain\Helper\Volunteer\VolunteerRepository;
use App\Domain\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="api/volunteer") */
class FetchController extends AbstractController
{
    use ControllerHelper;
    /** @Route("/current/", name="getCurrentVolunteer", methods={"GET"}) */
    public function getCurrent(VolunteerRepository $repository) : Response
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var Volunteer $volunteer */
        $volunteer = $repository->getById(new Id($user->getId()->getValue()));

        return $this->response($this->serializer->serialize([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'status' => $user->getStatus(),
            'role' => $user->getRoles(),
            'name' => $volunteer->getName(),
            'createdAt' => $user->getDateCreated(),
            'updatedAt' => $user->getDateUpdated()
        ]));
    }
}
