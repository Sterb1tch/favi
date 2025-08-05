<?php

declare(strict_types=1);

namespace App\Facade\Order;

use App\DTO\OrderData;
use App\DTO\Response\OrderCreatedResponse;
use App\DTO\Response\OrderDeliveryDateUpdatedResponse;
use App\DTO\UpdateOrderDeliveryDateData;
use App\Service\Order\OrderServiceInterface;
use App\Service\OrderItem\OrderItemServiceInterface;

readonly class OrderFacade implements OrderFacadeInterface
{
    public function __construct(
        private OrderServiceInterface $orderService,
        private OrderItemServiceInterface $orderItemService,
    ) {
    }

    public function createOrder(OrderData $orderData): OrderCreatedResponse
    {
        $order = $this->orderService->createOrder($orderData);
        $this->orderItemService->createFromOrderAndItems($order, $orderData->products);

        return new OrderCreatedResponse($order->getId());
    }

    public function updateDeliveryDate(
        int $orderId,
        UpdateOrderDeliveryDateData $deliveryDateData,
    ): OrderDeliveryDateUpdatedResponse {
        $order = $this->orderService->updateExpectedDeliveryDate($orderId, $deliveryDateData);

        return new OrderDeliveryDateUpdatedResponse($order);
    }
}
