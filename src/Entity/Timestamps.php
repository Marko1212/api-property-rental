<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Timestamps
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(groups: ['timestampable'])]
    private ?\DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(groups: ['timestampable'])]
    private ?\DateTimeInterface $updatedAt;

    #[ORM\PrePersist]
    public function createdAt()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    #[ORM\PreUpdate]
    public function updatedAt()
    {
        $this->updatedAt = new DateTime();
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
