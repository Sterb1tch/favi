<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\OrderItem;

interface OrderItemRepository
{
    public function save(OrderItem $orderItem): void;

    /**
     * @param OrderItem[] $orderItems
     */
    public function saveBulk(array $orderItems): void;
}
