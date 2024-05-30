<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\MappedSuperclass]
#[ORM\HasLifecycleCallbacks]
abstract class GenericEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['fetch', 'fetch:admin'])]
    private int $id;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    #[Groups('fetch:admin')]
    private DateTimeInterface $created;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    #[Groups('fetch:admin')]
    private DateTimeInterface $updated;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface $created): GenericEntity
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(DateTimeInterface $updated): GenericEntity
    {
        $this->updated = $updated;
        return $this;
    }
    
    #[ORM\PrePersist]
    public function executeOnPrePersist(): void
    {
        $this->updated = new DateTime();
        $this->created = new DateTime();
    }

    #[Orm\PreUpdate]
    public function executeOnPreUpdate(): void
    {
        $this->updated = new DateTime();
    }
}