<?php

declare(strict_types=1);

namespace App\Service\MailService;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailSenderService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(IMessage $message): bool
    {
        $email = (new Email())
            ->from($message->getEmailFrom()->getValue())
            ->to($message->getEmailTo()->getValue())
            ->subject($message->getSubject())
            ->text($message->getText())
            ->html($message->getBody());
        $this->mailer->send($email);
        return true;
    }
}
