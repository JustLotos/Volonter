<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\RegisterByEmail\Request;

use App\Domain\Flusher;
use App\Domain\User\Entity\Types\ConfirmToken;
use App\Domain\User\Entity\Types\Email;
use App\Domain\User\Entity\Types\Id;
use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\Types\Role;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserDTO;
use App\Domain\User\Events\UserCreatedEvent;
use App\Domain\User\Service\TokenService;
use App\Domain\User\UserRepository;
use App\Service\FlushService;
use App\Service\MailService\MailSenderService;
use App\Service\MailService\BaseMessage;
use App\Service\MailService\MailBuilderService;
use App\Service\ValidateService;
use DateTimeImmutable;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Handler
{
    private $flusher;
    private $validator;
    private $repository;
    private $sender;
    private $tokenizer;
    private $dispatcher;
    private $builder;
    private $generator;

    public function __construct(
        ValidateService $validator,
        TokenService $tokenizer,
        UserRepository $repository,
        FlushService $flusher,
        MailSenderService $sender,
        MailBuilderService $builder,
        EventDispatcherInterface $dispatcher,
        UrlGeneratorInterface $generator
    ) {
        $this->flusher = $flusher;
        $this->validator = $validator;
        $this->repository = $repository;
        $this->sender = $sender;
        $this->tokenizer = $tokenizer;
        $this->dispatcher = $dispatcher;
        $this->builder = $builder;
        $this->generator = $generator;
    }

    public function handle(Command $command): User
    {
        $this->validator->validate($command);

        $user = User::registerByEmail(
            Id::next(),
            new DateTimeImmutable(),
            Role::createUser(),
            new Email($command->email),
            new Password($command->password)
        );
        $user->requestRegisterConfirm($this->tokenizer->generateTokenByClass(ConfirmToken::class));

        $event = new UserCreatedEvent($user);
        $this->dispatcher->dispatch($event, UserCreatedEvent::NAME);
        $this->repository->add($user);
        $this->flusher->flush();



        $message = BaseMessage::getDefaultMessage(
            $user->getEmail(),
            'Регистрация в приложении Flash',
            'Подтверждение регистрации',
            $this->builder
                ->setParam('url', $this->generator->generate(
                    'registerConfirm',
                    ['token' => $user->getConfirmToken()->getToken()]
                ))
                ->setParam('token', $user->getConfirmToken()->getToken())
                ->build('mail/user/register.html.twig')
        );

        $this->sender->send($message);
        return $user;
    }
}