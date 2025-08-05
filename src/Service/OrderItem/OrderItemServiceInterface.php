<?php

declare(strict_types=1);

namespace App\Service\OrderItem;

use App\DTO\OrderItemData;
use App\Entity\Order;

interface OrderItemServiceInterface
{
    /**
     * @param OrderItemData[] $products
     */
    public function createFromOrderAndItems(Order $order, array $products): void;
}
