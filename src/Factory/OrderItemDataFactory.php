<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\OrderItemData;
use App\Entity\Order;
use App\Entity\OrderItem;

readonly class OrderItemDataFactory
{
    public function createBulkFromOrder(Order $order): array
    {
        $items = [];

        foreach ($order->getItems() as $item) {
            $items[] = $this->createFromOrderItem($item);
        }

        return $items;
    }

    public function createFromOrderItem(OrderItem $orderItem): OrderItemData
    {
        $orderItemData = $this->create();
        $orderItemData->productId = $orderItem->getProductId();
        $orderItemData->name = $orderItem->getName();
        $orderItemData->price = $orderItem->getPrice();
        $orderItemData->quantity = $orderItem->getQuantity();

        return $orderItemData;
    }

    private function create(): OrderItemData
    {
        return new OrderItemData();
    }
}
