<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['read:User', 'timestampable']],
    collectionOperations: [
        'get',
        'post',
    ],
    itemOperations: [
        'get',
        'put',
        'delete' => ['security' => 'is_granted("remove", object)']
    ]
)]
#[ApiFilter(filterClass: SearchFilter::class, properties: [
    'email' => 'ipartial',
    'name' => 'ipartial',
])]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['email'])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('email', message: "Un utilisateur ayant cette adresse email existe déjà")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestamps;

    final public const ROLE_ADMIN = 'ROLE_ADMIN';
    final public const ROLE_AGENT = 'ROLE_AGENT';
    final public const ROLE_MANAGER = 'ROLE_MANAGER';
    final public const ROLE_USER = 'ROLE_USER';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ['read:User', 'read:Property'])]
    private ?int $id;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(groups: ['read:User', 'read:Property'])]
    #[Assert\NotBlank(normalizer: 'trim', message: "L'email de l'utilisateur doit être renseigné")]
    #[Assert\Email(message: "L'adresse email doit avoir un format valide")]
    private ?string $email;

    #[ORM\Column]
    #[Groups(groups: ['read:User', 'read:Property'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(normalizer: 'trim', message: "Le mot de passe est obligatoire")]
    private ?string $password;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read:User', 'read:Property'])]
    #[Assert\NotBlank(normalizer: 'trim', message: "Le nom de l'utilisateur est obligatoire")]
    private ?string $name;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Property::class, orphanRemoval: true)]
    #[Groups(groups: ['read:User'])]
    #[ApiSubresource]
    private Collection $properties;

    #[ORM\ManyToMany(inversedBy: 'authorizedUsers', targetEntity: Property::class)]
    #[Groups(groups: ['read:User'])]
    private $propertiesToRead;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
        $this->propertiesToRead = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties->add($property);
            $property->setCreator($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getCreator() === $this) {
                $property->setCreator(null);
            }
        }

        return $this;
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

    public function getPropertiesToRead()
    {
        return $this->propertiesToRead;
    }

    public function addPropertyToRead(Property $property): self
    {
        if (!$this->propertiesToRead->contains($property)) {
            $this->propertiesToRead[] = $property;
        }

        return $this;
    }

    public function removePropertyToRead(Property $property): self
    {
        $this->propertiesToRead->removeElement($property);
        return $this;
    }
}
