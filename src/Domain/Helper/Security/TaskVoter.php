<?php

declare(strict_types=1);

namespace App\Domain\Helper\Security;

use App\Domain\Helper\Task\Entity\Task;
use App\Domain\User\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const NOT_FOUND_MESSAGE = 'Task not found';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        if (! in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if (! $subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (! $user instanceof User) {
            return false;
        }
        /** @var Task $task */
        $task = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($task, $user);
            case self::EDIT:
                return $this->canEdit($task, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canView(Task $task, User $user)
    {
        return $this->canEdit($task, $user);
    }

    private function canEdit(Task $task, User $user)
    {
        return $user->getId()->getValue() === $task->getVolunteer()->getId()->getValue();
    }
}
