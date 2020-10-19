<?php

declare(strict_types=1);

namespace App\Domain\User\Events;

use App\Domain\User\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserPasswordReset extends Event
{
    public const NAME = 'user.reset_password';

    /** @var User $user */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}