<?php

declare(strict_types=1);

namespace App\Security\Voters;

use App\Domain\Flash\Entity\Card\Card;
use App\Domain\User\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class CardVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const NOT_FOUND_MESSAGE = 'Card not found';

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

        if (! $subject instanceof Card) {
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
        /** @var Card $card */
        $card = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($card, $user);
            case self::EDIT:
                return $this->canEdit($card, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canView(Card $card, User $user)
    {
        if ($this->canEdit($card, $user)) {
            return true;
        }

        return false;
    }

    private function canEdit(Card $card, User $user)
    {
        return $user->getId()->getValue() === $card->getDeck()->getLearner()->getId()->getValue();
    }
}
