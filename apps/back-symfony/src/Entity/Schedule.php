<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trip;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['schedule:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['schedule:read'])]
    private ?\DateTimeInterface $eventDate = null;

    #[ORM\Column(length: 255)]
    #[Groups(['schedule:read'])]
    private ?string $eventDescription = null;

    #[ORM\ManyToOne(targetEntity: Trip::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['schedule:read'])]
    private ?Trip $trip = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): static
    {
        $this->eventDate = $eventDate;
        return $this;
    }

    public function getEventDescription(): ?string
    {
        return $this->eventDescription;
    }

    public function setEventDescription(string $eventDescription): static
    {
        $this->eventDescription = $eventDescription;
        return $this;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(Trip $trip): static
    {
        $this->trip = $trip;
        return $this;
    }
}
