<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BearRepository::class)]
final class Bear extends GenericEntity implements JsonSerializable
{
    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Groups('fetch')]
    private string $name;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Groups('fetch')]
    private string $location;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Groups('fetch')]
    private string $province;

    #[ORM\Column(nullable: false)]
    #[Assert\NotBlank]
    #[Groups('fetch')]
    private float $latitude;

    #[ORM\Column(nullable: false)]
    #[Assert\NotBlank]
    #[Groups('fetch')]
    private float $longitude;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'bears')]
    #[Groups('fetch:admin')]
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string')
    )]
    private Collection $hunters;

    public function __construct()
    {
        $this->hunters = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Bear
    {
        $this->name = $name;
        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): Bear
    {
        $this->location = $location;
        return $this;
    }

    public function getProvince(): string
    {
        return $this->province;
    }

    public function setProvince(string $province): Bear
    {
        $this->province = $province;
        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): Bear
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): Bear
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'location' => $this->getLocation(),
            'province' => $this->getProvince(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'hunters' => array_map(
                fn (User $hunter) => $hunter->getUserIdentifier(),
                $this->getHunters()->toArray(),
            ),
        ];
    }

    /**
     * @return Collection<int, User>
     */
    public function getHunters(): Collection
    {
        return $this->hunters;
    }

    public function addHunter(User $hunter): Bear
    {
        if (!$this->hunters->contains($hunter)) {
            $this->hunters->add($hunter);
        }

        return $this;
    }

    public function removeHunter(User $hunter): Bear
    {
        $this->hunters->removeElement($hunter);

        return $this;
    }
}
