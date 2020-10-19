<?php

declare(strict_types=1);

namespace App\Domain\User\Events;

use App\Domain\User\Entity\User;
use DomainException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents() : array
    {
        return [Events::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccessResponse'];
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event) : void
    {
        $data = $event->getData();
        /** @var User $user */
        $user = $event->getUser();
        if (! $user instanceof UserInterface) {
            throw new DomainException('Required class implements '. UserInterface::class .', '.get_class($user). ' given');
        }

        if ($user instanceof User) {
            $data['status'] = $user->getStatus();
            $data['email'] = $user->getEmail()->getValue();
            $data['role'] = $user->getRoles()[0];
            $data['accessToken'] = $data['token'];
            $data['id'] = $user->getId()->getValue();
            //unset($data['token']);
        }

        $event->setData($data);
    }
}
