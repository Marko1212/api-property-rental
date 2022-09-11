<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Locastic\ApiPlatformTranslationBundle\Model\AbstractTranslation;

#[ORM\Entity]
class PropertyTranslation extends AbstractTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Property::class, inversedBy: 'translations')]
    protected $translatable;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(groups: ['translations'])]
    private $description;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(groups: ['translations'])]
    protected $locale;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
