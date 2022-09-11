<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\PropertyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['read:Property', 'timestampable']],
    attributes: ['order' => ['price' => 'DESC']],
    collectionOperations: [
        'get',
        'post' => ['security_post_denormalize' => 'is_granted("create", object)'],
    ],
    itemOperations: [
        'get' => ['security' => 'is_granted("view", object)'],
        'put' => ['security_post_denormalize' => 'is_granted("edit", object) and is_granted("edit", previous_object)'],
        'delete' => ['security' => 'is_granted("remove", object)'],
    ],
    subresourceOperations: ['api_users_properties_get_subresource' => ['normalization_context' => ['groups' => ['properties_subresource']]]]
)]
#[ApiFilter(filterClass: SearchFilter::class, properties: [
    'city' => 'ipartial',
    'street' => 'ipartial',
    'name' => 'ipartial',
    'status' => 'iexact',
    'creator.name' => 'ipartial'
])]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['city', 'name', 'price'])]
#[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('name', message: "Une propriété ayant ce nom existe déjà")]
class Property
{
    use Timestamps;

    final public const STATUS_ACTIVE = 'ACTIVE';
    final public const STATUS_DELETED = 'DELETED';
    final public const STATUS_RENTED = 'RENTED';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ['read:Property', 'read:User', 'properties_subresource'])]
    private ?int $id;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(groups: ['read:Property', 'read:User', 'properties_subresource'])]
    #[Assert\NotBlank(normalizer: 'trim', message: "Le nom de la propriété est obligatoire")]
    private ?string $name;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read:Property', 'read:User', 'properties_subresource'])]
    #[Assert\NotBlank(normalizer: 'trim', message: "Le nom de la ville est obligatoire")]
    private ?string $city;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read:Property', 'read:User', 'properties_subresource'])]
    #[Assert\NotBlank(normalizer: 'trim', message: "Le nom de la rue est obligatoire")]
    private ?string $street;

    #[ORM\Column]
    #[Groups(groups: ['read:Property', 'read:User', 'properties_subresource'])]
    #[Assert\NotBlank(normalizer: 'trim', message: "Le prix est obligatoire")]
    #[Assert\Type(type: 'numeric')]
    #[Assert\Positive]
    private ?float $price;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(groups: ['read:Property', 'read:User', 'properties_subresource'])]
    #[Assert\NotBlank(normalizer: 'trim', message: "Le nombre de chambres est obligatoire")]
    #[Assert\Type(type: 'integer')]
    #[Assert\Positive]
    private ?int $numberOfRooms;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read:Property', 'read:User', 'properties_subresource'])]
    #[Assert\NotBlank(normalizer: 'trim', message: "Le statut de la propriété est obligatoire")]
    #[Assert\Choice(choices: ["ACTIVE", "DELETED", "RENTED", "active", "deleted", "rented"], message: "Le statut doit être ACTIVE, DELETED ou RENTED")]
    private ?string $status;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(groups: ['read:Property', 'read:User', 'properties_subresource'])]
    private ?string $description;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ['read:Property'])]
    #[Assert\NotBlank(normalizer: 'trim')]
    #[Assert\Valid]
    private ?User $creator;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getNumberOfRooms(): ?int
    {
        return $this->numberOfRooms;
    }

    public function setNumberOfRooms(int $numberOfRooms): self
    {
        $this->numberOfRooms = $numberOfRooms;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }
}
