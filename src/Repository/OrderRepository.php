<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;

interface OrderRepository
{
    public function save(Order $order): void;

    public function findOrderByOrderId(string $orderId): ?Order;

    public function getById(int $id): Order;
}
