<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Register\ByEmail\Confirm;

use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Service\FlushService;
use App\Service\MailService\MailSenderService;
use App\Service\MailService\BaseMessage;
use App\Service\MailService\MailBuilderService;
use App\Service\ValidateService;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private $repository;
    private $flusher;
    private $validator;
    private $sender;
    private $builder;

    public function __construct(
        UserRepository $repository,
        FlushService $flusher,
        ValidateService $validator,
        MailSenderService $sender,
        MailBuilderService $builder
    ) {
        $this->repository = $repository;
        $this->flusher = $flusher;
        $this->validator = $validator;
        $this->sender = $sender;
        $this->builder = $builder;
    }

    public function handle(Command $token): void
    {
        $this->validator->validate($token);
        if (!$user = $this->repository->findByConfirmToken($token->value)) {
            throw new DomainException('Incorrect or confirmed token.');
        }

        $user->confirmRegisterByEmail(new DateTimeImmutable());
        $this->flusher->flush();
        $this->sendMessage($user);
    }

    public function sendMessage(User $user): void
    {
        $message = BaseMessage::getDefaultMessage(
            $user->getEmail(),
            'Регистрация в приложении Flash',
            'Подтверждение регистрации',
            $this->builder->build('mail/user/reset/byEmail/confirm.html.twig')
        );

        $this->sender->send($message);
    }
}
