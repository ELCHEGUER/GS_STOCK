<?php
namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $order_date = null;

    #[ORM\Column]
    private ?int $total_amount = null;

    #[ORM\Column(length: 255)]
    private ?string $payement_status = null;

    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['persist', 'remove'])]
    private Collection $orderItems;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int 
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->total_amount;
    }

    public function setTotalAmount(int $total_amount): static
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getPayementStatus(): ?string
    {
        return $this->payement_status;
    }

    public function setPayementStatus(string $payement_status): static
    {
        $this->payement_status = $payement_status;

        return $this;
    }

    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }
}
