<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\OrderData;
use App\Entity\Order;

class OrderFactory
{
    public function createFromOrderData(OrderData $orderData): Order
    {
        return new Order($orderData);
    }
}
