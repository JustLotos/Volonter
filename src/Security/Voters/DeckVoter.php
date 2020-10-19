<?php

declare(strict_types=1);

namespace App\Security\Voters;

use App\Domain\Flash\Entity\Deck\Deck;
use App\Domain\User\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class DeckVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const NOT_FOUND_MESSAGE = 'Deck not found';

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

        if (! $subject instanceof Deck) {
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

        /** @var Deck $deck */
        $deck = $subject;


        switch ($attribute) {
            case self::VIEW:
                return $this->canView($deck, $user);
            case self::EDIT:
                return $this->canEdit($deck, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canView(Deck $deck, User $user)
    {
        if ($this->canEdit($deck, $user)) {
            return true;
        }
        return false;
    }

    private function canEdit(Deck $deck, User $user)
    {
        return $user->getId()->getValue() === $deck->getLearner()->getId()->getValue();
    }
}
