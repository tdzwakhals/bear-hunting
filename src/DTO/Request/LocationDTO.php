<?php

declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class LocationDTO
{
    #[Assert\NotBlank]
    private float $latitude;
    #[Assert\NotBlank]
    private float $longitude;
    private int $radius = 25;

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): LocationDTO
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): LocationDTO
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getRadius(): int
    {
        return $this->radius;
    }

    public function setRadius(int $radius): LocationDTO
    {
        $this->radius = $radius;
        return $this;
    }
}