<?php

declare(strict_types=1);

namespace App\Service\MailService;

use App\Domain\User\Entity\Types\Email;

interface IMessage
{
    public function getBody(): string;
    public function getEmailTo(): Email;
    public function getSubject(): string;
    public function getText(): string;
    public function getEmailFrom(): Email;

}
