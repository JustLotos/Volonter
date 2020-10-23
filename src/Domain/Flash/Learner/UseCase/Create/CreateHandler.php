<?php

declare(strict_types=1);

namespace App\Domain\Flash\Learner\UseCase\Create;

use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\Entity\Types\Id;
use App\Domain\Flash\Learner\LearnerRepository;
use App\Domain\Flusher;
use App\Domain\User\Entity\User;
use App\Service\FlushService;

class CreateHandler
{
    private $repository;
    private $flusher;

    public function __construct(
        LearnerRepository $repository,
        FlushService $flusher
    ) {
        $this->repository = $repository;
        $this->flusher = $flusher;
    }

    public function handle(User $user): Learner
    {
        $learner = Learner::create(new Id($user->getId()->getValue()));

        $this->repository->add($learner);
        $this->flusher->flush();

        return $learner;
    }
}
