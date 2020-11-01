<?php

declare(strict_types=1);

namespace App\Domain\Helper\Security;

use App\Domain\Helper\Comment\Entity\Comment;
use App\Domain\User\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class CommentVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const NOT_FOUND_MESSAGE = 'Comment not found';

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

        if (! $subject instanceof Comment) {
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
        /** @var Comment $comment */
        $comment = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($comment, $user);
            case self::EDIT:
                return $this->canEdit($comment, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canView(Comment $comment, User $user)
    {
        return $this->canEdit($comment, $user);
    }

    private function canEdit(Comment $comment, User $user)
    {
        return $user->getId()->getValue() === $comment->getVolunteer()->getId()->getValue();
    }
}
