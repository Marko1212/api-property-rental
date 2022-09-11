<?php

namespace App\Events;

use App\Entity\User;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordUpdateListener
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * {@inheritDoc}
     */
    public function preUpdate(User $user, PreUpdateEventArgs $event): void
    {
        if ($event->hasChangedField('password')) {
            $hash = $this->hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);
        }
    }
}
