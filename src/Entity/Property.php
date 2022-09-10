<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\PropertyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['read:Property', 'timestampable']],
    attributes: ['order' => ['price' => 'DESC']],
    collectionOperations: [
        'get',
        'post'
    ],
    itemOperations: [
        'get' => ['security' => "is_granted('ROLE_USER')"],
        'put' => ['security' => "is_granted('ROLE_USER')"],
        'delete' => ['security' => "is_granted('ROLE_USER')"]
    ],
    subresourceOperations: ['api_users_properties_get_subresource' => ['normalization_context' => ['groups' => ['properties_subresource']]]]
)]
#[ApiFilter(filterClass: SearchFilter::class, properties: [
    'city' => 'partial',
    'street' => 'partial',
    'name' => 'partial',
    'owner.name' => 'ipartial'
])]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['city', 'name', 'price'])]
#[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[ORM\HasLifecycleCallbacks]
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

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read:Property', 'write:Property', 'update:Property', 'read:User', 'properties_subresource'])]
    private ?string $name;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read:Property', 'write:Property', 'update:Property', 'read:User', 'properties_subresource'])]
    private ?string $city;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read:Property', 'write:Property', 'update:Property', 'read:User', 'properties_subresource'])]
    private ?string $street;

    #[ORM\Column]
    #[Groups(groups: ['read:Property', 'write:Property', 'update:Property', 'read:User', 'properties_subresource'])]
    private ?float $price;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(groups: ['read:Property', 'write:Property', 'update:Property', 'read:User', 'properties_subresource'])]
    private ?int $numberOfRooms;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read:Property', 'write:Property', 'update:Property', 'read:User', 'properties_subresource'])]
    private ?string $status;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(groups: ['read:Property', 'write:Property', 'update:Property', 'read:User', 'properties_subresource'])]
    private ?string $description;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ['read:Property', 'write:Property', 'update:Property'])]
    private ?User $owner;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
