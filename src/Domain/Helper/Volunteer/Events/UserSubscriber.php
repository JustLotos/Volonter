<?php

declare(strict_types=1);

namespace App\Domain\Helper\Task\Events;


use App\Domain\Helper\Task\UseCase\Create\CreateHandler;
use App\Domain\User\Events\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    private $handler;

    public function __construct(CreateHandler $handler)
    {
        $this->handler = $handler;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserCreatedEvent::NAME => 'onUserCreated',
        ];
    }

    public function onUserCreated(UserCreatedEvent $event)
    {
        $this->handler->handle($event->getUser());
    }
}