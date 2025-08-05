<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\DTO\OrderData;
use App\DTO\UpdateOrderDeliveryDateData;
use App\Entity\Order;
use App\Exception\OrderAlreadyExistsException;
use App\Factory\OrderDataFactory;
use App\Factory\OrderFactory;
use App\Repository\OrderRepository;

readonly class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderDataFactory $orderDataFactory,
        private OrderFactory $orderFactory,
    ) {
    }

    public function createOrder(OrderData $orderData): Order
    {
        $orderId = $orderData->orderId;
        $existingOrder = $this->orderRepository->findOrderByOrderId($orderId);

        if ($existingOrder !== null) {
            throw new OrderAlreadyExistsException(sprintf('Order with ID %s already exists', $orderId));
        }

        $order = $this->orderFactory->createFromOrderData($orderData);
        $this->orderRepository->save($order);

        return $order;
    }

    public function updateExpectedDeliveryDate(int $orderId, UpdateOrderDeliveryDateData $deliveryDateData): Order
    {
        $order = $this->orderRepository->getById($orderId);
        $orderData = $this->orderDataFactory->createForDeliveryDate($order, $deliveryDateData->expectedDeliveryDate);

        $order->edit($orderData);
        $this->orderRepository->save($order);

        return $order;
    }
}
