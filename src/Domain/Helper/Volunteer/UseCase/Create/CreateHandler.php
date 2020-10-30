<?php

declare(strict_types=1);

namespace App\Domain\Helper\Volunteer\UseCase\Create;

use App\Domain\Helper\Volunteer\Entity\Volunteer;
use App\Domain\Helper\Volunteer\Entity\Types\Id;
use App\Domain\Helper\Volunteer\VolunteerRepository;
use App\Domain\User\Entity\User;
use App\Service\FlushService;

class CreateHandler
{
    private $repository;
    private $flusher;

    public function __construct(
        VolunteerRepository $repository,
        FlushService $flusher
    ) {
        $this->repository = $repository;
        $this->flusher = $flusher;
    }

    public function handle(User $user): Volunteer
    {
        $learner = Volunteer::create(new Id($user->getId()->getValue()));

        $this->repository->add($learner);
        $this->flusher->flush();

        return $learner;
    }
}
