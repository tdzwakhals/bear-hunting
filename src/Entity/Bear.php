<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BearRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BearRepository::class)]
final class Bear extends GenericEntity implements JsonSerializable
{
    #[ORM\Column(length: 255, unique: true, nullable: false)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    private string $location;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    private string $province;

    #[ORM\Column(nullable: false)]
    #[Assert\NotBlank]
    private float $latitude;

    #[ORM\Column(nullable: false)]
    #[Assert\NotBlank]
    private float $longitude;

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
            'name' => $this->name,
            'location' => $this->location,
            'province' => $this->province,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
