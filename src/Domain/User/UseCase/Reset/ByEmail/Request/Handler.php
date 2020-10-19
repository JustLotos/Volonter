<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Reset\ByEmail\Request;

use App\Domain\User\Entity\Types\ConfirmToken;
use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use App\Domain\User\Service\TokenService;
use App\Service\FlushService;
use App\Service\MailService\BaseMessage;
use App\Service\MailService\MailBuilderService;
use App\Service\MailService\MailSenderService;
use App\Service\ValidateService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Handler
{
    private $flusher;
    private $repository;
    private $tokenizer;
    private $sender;
    private $validator;
    private $builder;
    private $generator;

    public function __construct(
        ValidateService $validator,
        FlushService $flusher,
        UserRepository $repository,
        TokenService $tokenizer,
        MailSenderService $sender,
        MailBuilderService $builder,
        UrlGeneratorInterface $generator
    ) {
        $this->flusher = $flusher;
        $this->repository = $repository;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->validator = $validator;
        $this->builder = $builder;
        $this->generator = $generator;
    }

    public function handle(Command $command): User
    {
        $this->validator->validate($command);
        /** @var User $user */
        $user = $this->repository->getByEmail($command->email);

        $user->requestResetPassword($this->tokenizer->generateTokenByClass(ConfirmToken::class));
        $this->flusher->flush();
        $this->sendConfirmMessage($user);
        return $user;
    }

    public function sendConfirmMessage(User $user): void
    {
        $message = BaseMessage::getDefaultMessage(
            $user->getEmail(),
            'Восстановление доступа в приложении FLashBack',
            $this->builder->setParam('url', $this->generator->generate(
                'resetByEmailGetForm',
                ['token' => $user->getConfirmToken()->getToken()],
                UrlGeneratorInterface::ABSOLUTE_URL
            ))
            ->setParam('token', $user->getConfirmToken()->getToken())
            ->build('mail/user/reset/byEmail/request.html.twig')
        );

        $this->sender->send($message);
    }
}
