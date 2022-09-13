<?php

namespace App\Security;

use App\Entity\{Property, User};
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ResourceUserVoter extends Voter
{
    final public const REMOVE = 'remove';

    final public const ATTRIBUTES = [
        self::REMOVE
    ];

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, self::ATTRIBUTES)) {
            return false;
        }
        return $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::REMOVE => $this->canRemove($subject, $user),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canRemove(User $resourceUser, User $user): bool
    {
        if (
            $user !== $resourceUser &&
            $this->security->isGranted('ROLE_ADMIN')
        ) {
            return true;
        }

        return false;
    }
}
