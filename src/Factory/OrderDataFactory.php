<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\OrderData;
use App\Entity\Order;

class OrderDataFactory
{
    public function __construct(
        private OrderItemDataFactory $factory,
    ) {
    }

    public function createForDeliveryDate(Order $order, \DateTime $deliveryDate): OrderData
    {
        $orderData = $this->create();
        $orderData->orderId = $order->getOrderId();
        $orderData->partnerId = $order->getPartnerId();
        $orderData->value = $order->getValue();
        $orderData->expectedDeliveryDate = $deliveryDate;
        $orderData->products = $this->factory->createBulkFromOrder($order);

        return $orderData;
    }

    private function create(): OrderData
    {
        return new OrderData();
    }
}
