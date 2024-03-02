<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

	#[ORM\Column(type: Types::STRING, length: 50)]
	private ?string $externalId = null;

	#[ORM\Column(type: Types::STRING, length: 50)]
	private ?string $number = null;

    #[ORM\Column]
    private ?int $totalInCents = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;
	
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $integrationRef = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalInCents(): ?int
    {
        return $this->totalInCents;
    }

    public function setTotalInCents(int $totalInCents): static
    {
        $this->totalInCents = $totalInCents;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIntegrationRef(): ?int
    {
        return $this->integrationRef;
    }

    public function setIntegrationRef(int $integrationRef): static
    {
        $this->integrationRef = $integrationRef;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }
}
