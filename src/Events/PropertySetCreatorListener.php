<?php

namespace App\Events;

use App\Entity\Property;
use Symfony\Component\Security\Core\Security;

class PropertySetCreatorListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Property $property)
    {
        if ($this->security->getUser()) {
            $property->setCreator($this->security->getUser());
        }
    }
}
