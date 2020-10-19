<?php

declare(strict_types=1);

namespace App\Domain\User\Events;

use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashPasswordListener implements EventSubscriber
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->encodePassword($entity);
    }
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->encodePassword($entity);
        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        //$meta = $em->getClassMetadata(get_class($entity));
        //$em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function getSubscribedEvents(): array
    {
        return ['prePersist', 'preUpdate'];
    }

    private function encodePassword(User $entity): void
    {
        if (!$entity->getPassword()) {
            return;
        }
        $encoded = $this->passwordEncoder->encodePassword($entity, $entity->getPassword());
        $entity->setPassword(new Password($encoded));
    }
}
