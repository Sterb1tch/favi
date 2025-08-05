<?php

declare(strict_types=1);

namespace App\Entity;

use App\DTO\OrderItemData;
use App\Trait\EntityIdIdentifier;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class OrderItem
{
    use EntityIdIdentifier;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private Order $order;

    #[ORM\Column(length: 255, nullable: false)]
    private string $productId;

    #[ORM\Column(length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(nullable: false)]
    private float $price;

    #[ORM\Column(nullable: false)]
    private int $quantity;

    public function __construct(Order $order, OrderItemData $orderItemData)
    {
        $this->order = $order;
        $this->setAttributes($orderItemData);
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    private function setAttributes(OrderItemData $orderItemData): void
    {
        $this->productId = $orderItemData->productId;
        $this->name = $orderItemData->name;
        $this->price = $orderItemData->price;
        $this->quantity = $orderItemData->quantity;
    }
}
