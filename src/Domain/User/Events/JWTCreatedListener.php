<?php

declare(strict_types=1);

namespace App\Domain\User\Events;

use DateTime;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener implements EventSubscriberInterface
{
    public $period;

    /** @var RequestStack */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->period = $_ENV['REMEMBER_ME_EXPIRATION_DAYS'];
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents() : array
    {
        return [Events::JWT_CREATED => 'onJWTCreated'];
    }

    /**
     * @param JWTCreatedEvent $event
     * @throws Exception
     */
    public function onJWTCreated(JWTCreatedEvent $event) : void
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request->getContentType() !== 'json') {
            return;
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data['rememberMe']) && empty($data['remember_me'])) {
            return;
        }

        $expiration = new DateTime('+' . $this->period. ' days');
        $payload = $event->getData();
        $payload['exp'] = $expiration->getTimestamp();
        $event->setData($payload);
    }
}
