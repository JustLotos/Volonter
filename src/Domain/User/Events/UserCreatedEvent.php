<?php

declare(strict_types=1);

namespace App\Domain\User\Events;

use App\Domain\User\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserCreatedEvent extends Event
{
    public const NAME = 'new_user.created';

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