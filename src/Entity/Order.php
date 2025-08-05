<?php

declare(strict_types=1);

namespace App\Entity;

use App\DTO\OrderData;
use App\Exception\OrderDeliveryDateException;
use App\Trait\EntityIdIdentifier;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`order`')]
class Order
{
    use EntityIdIdentifier;

    #[ORM\Column(length: 255, nullable: false)]
    private string $orderId;

    #[ORM\Column(length: 255, nullable: false)]
    private string $partnerId;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private \DateTimeInterface $expectedDeliveryDate;

    #[ORM\Column(nullable: false)]
    private float $value;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    public function __construct(OrderData $orderData)
    {
        $this->items = new ArrayCollection();
        $this->setAttributes($orderData);
    }

    public function edit(OrderData $orderData): void
    {
        $this->setAttributes($orderData);
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getPartnerId(): string
    {
        return $this->partnerId;
    }

    public function getExpectedDeliveryDate(): \DateTimeInterface
    {
        return $this->expectedDeliveryDate;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    private function setAttributes(OrderData $orderData): void
    {
        $this->orderId = $orderData->orderId;
        $this->partnerId = $orderData->partnerId;
        $this->value = $orderData->value;

        if ($orderData->expectedDeliveryDate < new \DateTime()) {
            throw new OrderDeliveryDateException('Delivery date can not be in the past.');
        }

        $this->expectedDeliveryDate = $orderData->expectedDeliveryDate;
    }
}
