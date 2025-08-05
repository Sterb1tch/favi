<?php

declare(strict_types=1);

namespace App\Facade\Order;

use App\DTO\OrderData;
use App\DTO\Response\OrderCreatedResponse;
use App\DTO\Response\OrderDeliveryDateUpdatedResponse;
use App\DTO\UpdateOrderDeliveryDateData;

interface OrderFacadeInterface
{
    public function createOrder(OrderData $orderData): OrderCreatedResponse;

    public function updateDeliveryDate(
        int $orderId,
        UpdateOrderDeliveryDateData $deliveryDateData,
    ): OrderDeliveryDateUpdatedResponse;
}
