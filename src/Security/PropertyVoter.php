<?php

namespace App\Security;

use App\Entity\{Property, User};
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PropertyVoter extends Voter
{
    final public const CREATE = 'create';
    final public const EDIT = 'edit';
    final public const REMOVE = 'remove';
    final public const VIEW = 'view';

    final public const ATTRIBUTES = [
        self::CREATE,
        self::EDIT,
        self::REMOVE,
        self::VIEW,
    ];

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, self::ATTRIBUTES)) {
            return false;
        }
        return $subject instanceof Property;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::CREATE => $this->canCreate($subject, $user),
            self::VIEW => $this->canView($subject, $user),
            self::EDIT => $this->canEdit($subject, $user),
            self::REMOVE => $this->canRemove($subject, $user),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canCreate(Property $property, User $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_MANAGER')) {
            return true;
        }

        return false;
    }

    private function canView(Property $property, User $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($this->security->isGranted('ROLE_MANAGER') && $user === $property->getCreator()) {
            return true;
        }

        if ($this->security->isGranted('ROLE_AGENT') && $user->getPropertiesToRead()->contains($property)) {
            return true;
        }

        return false;
    }

    private function canEdit(Property $property, User $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($this->security->isGranted('ROLE_MANAGER') && $user === $property->getCreator()) {
            return true;
        }

        return false;
    }

    private function canRemove(Property $property, User $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($this->security->isGranted('ROLE_MANAGER') && $user === $property->getCreator()) {
            return true;
        }

        return false;
    }
}
