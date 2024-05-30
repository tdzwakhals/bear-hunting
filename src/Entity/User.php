<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use OpenApi\Attributes as OA;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
final class User extends GenericEntity implements UserInterface, PasswordAuthenticatedUserInterface, JsonSerializable
{
    #[ORM\Column(length: 180, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups('fetch:admin')]
    private string $email;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups('fetch:admin')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups('fetch:admin')]
    private ?DateTimeInterface $lastLogin = null;

    /**
     * @var Collection<int, Bear>
     */
    #[ORM\ManyToMany(targetEntity: Bear::class, mappedBy: 'hunters')]
    private Collection $bears;

    public function __construct()
    {
        $this->bears = new ArrayCollection();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
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
        return $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastLogin(): ?DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?DateTimeInterface $lastLogin): User
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return Collection<int, Bear>
     */
    public function getBears(): Collection
    {
        return $this->bears;
    }

    public function addBear(Bear $bear): User
    {
        if (!$this->bears->contains($bear)) {
            $this->bears->add($bear);
            $bear->addHunter($this);
        }

        return $this;
    }

    public function removeBear(Bear $bear): User
    {
        if ($this->bears->removeElement($bear)) {
            $bear->removeHunter($this);
        }

        return $this;
    }

    #[Groups('fetch:admin')]
    #[OA\Property(type: 'integer')]
    public function getBearsHunted(): int
    {
        return $this->getBears()->count();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'roles' => $this->getRoles(),
            'bears_hunted' => $this->getBearsHunted(),
            'last_login' => $this->getLastLogin()?->format('d-m-Y H:i:s'),
            'created' => $this->getCreated()->format('d-m-Y H:i:s'),
            'updated' => $this->getUpdated()->format('d-m-Y H:i:s'),
        ];
    }
}
