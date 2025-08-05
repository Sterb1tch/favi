<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\DTO\OrderData;
use App\DTO\UpdateOrderDeliveryDateData;
use App\Entity\Order;

interface OrderServiceInterface
{
    public function createOrder(OrderData $orderData): Order;

    public function updateExpectedDeliveryDate(int $orderId, UpdateOrderDeliveryDateData $deliveryDateData): Order;
}
