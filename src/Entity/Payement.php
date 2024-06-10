<?php

namespace App\Entity;

use App\Repository\PayementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PayementRepository::class)]
class Payement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $FullName = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $payementDate = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column]
    private ?int $CVV = null;

    #[ORM\Column]
    private ?int $creditNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $AcceptedCard = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->FullName;
    }

    public function setFullName(string $FullName): static
    {
        $this->FullName = $FullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPayementDate(): ?\DateTimeInterface
    {
        return $this->payementDate;
    }

    public function setPayementDate(\DateTimeInterface $payementDate): static
    {
        $this->payementDate = $payementDate;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCVV(): ?int
    {
        return $this->CVV;
    }

    public function setCVV(int $CVV): static
    {
        $this->CVV = $CVV;

        return $this;
    }

    public function getCreditNumber(): ?int
    {
        return $this->creditNumber;
    }

    public function setCreditNumber(int $creditNumber): static
    {
        $this->creditNumber = $creditNumber;

        return $this;
    }

    public function getAcceptedCard(): ?string
    {
        return $this->AcceptedCard;
    }

    public function setAcceptedCard(string $AcceptedCard): static
    {
        $this->AcceptedCard = $AcceptedCard;

        return $this;
    }
}
