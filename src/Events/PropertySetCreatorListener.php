<?php

namespace App\Events;

use App\Entity\Property;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Security;

class PropertySetCreatorListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritDoc}
     */
    public function preUpdate(Property $property, PreUpdateEventArgs $event): void
    {
        if ($event->hasChangedField('creator')) {
            $property->setCreator($event->getOldValue('creator'));
        }
    }

    public function prePersist(Property $property)
    {
        if ($this->security->getUser()) {
            $property->setCreator($this->security->getUser());
        }
    }
}
