<?php

declare(strict_types=1);

namespace App\Service\MailService;

use App\Domain\User\Entity\Types\Email;

class BaseMessage implements IMessage
{
    use MailTrait;

    private $emailTo;
    private $body;
    private $subject;
    private $text;
    private $emailFrom;

    public function __construct(
        Email $emailTo,
        string $body,
        string $subject,
        string $text,
        Email $emailFrom
    ) {
        $this->emailTo = $emailTo;
        $this->body = $body;
        $this->subject = $subject;
        $this->text = $text;
        $this->emailFrom = $emailFrom;
    }

    public static function getDefaultMessage(
        Email $emailTo,
        string $subject,
        string $body
    ): self {
        return new self($emailTo, $body, $subject, '', self::getDefaultFromAddress());
    }


    public function getBody(): string
    {
        return $this->body;
    }

    public function getEmailTo(): Email
    {
        return $this->emailTo;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getEmailFrom(): Email
    {
        return $this->emailFrom;
    }
}
