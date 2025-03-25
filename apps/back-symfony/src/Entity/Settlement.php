<?php

namespace App\Entity;

use App\Repository\SettlementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettlementRepository::class)]
class Settlement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $payerId = null;

    #[ORM\Column]
    private ?int $payeeId = null;

    #[ORM\Column]
    private ?int $tripId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPayerId(): ?int
    {
        return $this->payerId;
    }

    public function setPayerId(int $payerId): static
    {
        $this->payerId = $payerId;

        return $this;
    }

    public function getPayeeId(): ?int
    {
        return $this->payeeId;
    }

    public function setPayeeId(int $payeeId): static
    {
        $this->payeeId = $payeeId;

        return $this;
    }

    public function getTripId(): ?int
    {
        return $this->tripId;
    }

    public function setTripId(int $tripId): static
    {
        $this->tripId = $tripId;

        return $this;
    }
}
