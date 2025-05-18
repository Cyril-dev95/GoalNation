<?php

namespace App\Entity;

use App\Repository\PaymentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentsRepository::class)]
class Payments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $payment_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(length: 50)]
    private ?string $payment_method = null;

    #[ORM\Column(length: 100)]
    private ?string $status = null;

    #[ORM\Column(length: 3)]
    private ?string $currency = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(targetEntity: Orders::class, inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Orders $order = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $stripe_payment_id = null;

    public function __construct()
    {
        $this->payment_date = new \DateTimeImmutable();
        $this->created_at = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function onPrePersist()
    {
        if (null === $this->payment_date) {
            $this->payment_date = new \DateTimeImmutable();
        }
        if (null === $this->created_at) {
            $this->created_at = new \DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPaymentDate(\DateTimeInterface $payment_date): static
    {
        $this->payment_date = $payment_date;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): static
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getOrder(): ?Orders
    {
        return $this->order;
    }

    public function setOrder(?Orders $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function setStripePaymentId(?string $stripePaymentId): self
    {
        $this->stripe_payment_id = $stripePaymentId;
        return $this;
    }

    public function getStripePaymentId(): ?string
    {
        return $this->stripe_payment_id;
    }
}