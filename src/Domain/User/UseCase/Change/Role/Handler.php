<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Change\Role;

use App\Domain\User\Entity\Types\Id;
use App\Domain\User\Entity\Types\Role;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Domain\User\Service\PasswordEncoder;
use App\Service\FlushService;
use App\Service\MailService\BaseMessage;
use App\Service\MailService\MailBuilderService;
use App\Service\MailService\MailSenderService;
use App\Service\ValidateService;

class Handler
{
    private $repository;
    private $flusher;
    private $validator;
    private $sender;
    private $builder;

    public function __construct(
        UserRepository $repository,
        ValidateService $validator,
        FlushService $flusher,
        MailSenderService $sender,
        MailBuilderService $builder
    ) {
        $this->repository = $repository;
        $this->flusher = $flusher;
        $this->validator = $validator;
        $this->sender = $sender;
        $this->builder = $builder;
    }

    public function handle(Command $command): void
    {
        $this->validator->validate($command);

        /** @var User $user */
        $user = $this->repository->get(new Id($command->id));

        $user->changeRole(new Role($command->role));
        $this->flusher->flush();
    }
}
