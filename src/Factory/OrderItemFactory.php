<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\OrderItemData;
use App\Entity\Order;
use App\Entity\OrderItem;

class OrderItemFactory
{
    public function createFromOrderAndData(Order $order, OrderItemData $orderItemData): OrderItem
    {
        return new OrderItem($order, $orderItemData);
    }
}
