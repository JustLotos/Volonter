<?php

declare(strict_types=1);

namespace App\Domain\User\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationFailureListener implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents() : array
    {
        return [Events::AUTHENTICATION_FAILURE => 'onAuthenticationFailureResponse'];
    }

    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event) : void
    {
        $response = new Response(
            json_encode(
                ['errors' => ['auth'=>'invalid credentials']]
            ),
            Response::HTTP_UNAUTHORIZED
        );
        $event->setResponse($response);
    }
}
