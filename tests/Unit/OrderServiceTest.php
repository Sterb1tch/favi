<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\DTO\OrderData;
use App\DTO\UpdateOrderDeliveryDateData;
use App\Entity\Order;
use App\Exception\OrderAlreadyExistsException;
use App\Exception\OrderNotFoundException;
use App\Factory\OrderDataFactory;
use App\Factory\OrderFactory;
use App\Repository\OrderRepository;
use App\Service\Order\OrderService;
use PHPUnit\Framework\MockObject\MockObject;

class OrderServiceTest extends BaseUnitTest
{
    private OrderRepository&MockObject $orderRepository;
    private OrderDataFactory&MockObject $orderDataFactory;
    private OrderFactory&MockObject $orderFactory;
    private OrderService $orderService;

    public function testCreateOrderSuccessfully(): void
    {
        $orderId = '123';
        $orderData = $this->createMock(OrderData::class);
        $orderData->orderId = $orderId;

        $order = $this->createMock(Order::class);

        $this->orderRepository
            ->expects($this->once())
            ->method('findOrderByOrderId')
            ->with($orderId)
            ->willReturn(null);

        $this->orderFactory
            ->expects($this->once())
            ->method('createFromOrderData')
            ->with($orderData)
            ->willReturn($order);

        $this->orderRepository
            ->expects($this->once())
            ->method('save')
            ->with($order);

        $result = $this->orderService->createOrder($orderData);

        self::assertSame($order, $result);
    }

    public function testCreateOrderThrowsIfAlreadyExists(): void
    {
        $orderId = '456';
        $orderData = $this->createMock(OrderData::class);
        $orderData->orderId = $orderId;

        $existingOrder = $this->createMock(Order::class);

        $this->orderRepository
            ->expects($this->once())
            ->method('findOrderByOrderId')
            ->with($orderId)
            ->willReturn($existingOrder);

        $this->expectException(OrderAlreadyExistsException::class);
        $this->expectExceptionMessage("Order with ID $orderId already exists");

        $this->orderService->createOrder($orderData);
    }

    public function testUpdateExpectedDeliveryDate(): void
    {
        $orderId = 789;
        $deliveryDate = new \DateTime('2025-12-01');

        $updateData = new UpdateOrderDeliveryDateData();
        $updateData->expectedDeliveryDate = $deliveryDate;

        $orderData = new OrderData();
        $orderData->orderId = '123';
        $orderData->value = 100.0;
        $orderData->partnerId = '456';
        $orderData->products = $this->getOrderItems();
        $orderData->expectedDeliveryDate = $deliveryDate;

        $order = $this->createMock(Order::class);

        $this->orderRepository
            ->expects($this->once())
            ->method('getById')
            ->with($orderId)
            ->willReturn($order);

        $order
            ->expects($this->once())
            ->method('edit')
            ->with($orderData);

        $this->orderRepository
            ->expects($this->once())
            ->method('save')
            ->with($order);

        $this->orderDataFactory
            ->expects($this->once())
            ->method('createForDeliveryDate')
            ->with($order, $updateData->expectedDeliveryDate)
            ->willReturn($orderData);

        $result = $this->orderService->updateExpectedDeliveryDate($orderId, $updateData);

        self::assertSame($order, $result);
    }

    public function testUpdateExpectedDeliveryDateThrowsIfOrderNotFound(): void
    {
        $orderId = 999;
        $deliveryDate = new \DateTime('2025-12-01');

        $updateData = new UpdateOrderDeliveryDateData();
        $updateData->expectedDeliveryDate = $deliveryDate;

        $this->orderRepository
            ->expects($this->once())
            ->method('getById')
            ->with($orderId)
            ->willThrowException(new OrderNotFoundException("Order with ID $orderId not found"));

        $this->expectException(OrderNotFoundException::class);
        $this->expectExceptionMessage("Order with ID $orderId not found");

        $this->orderService->updateExpectedDeliveryDate($orderId, $updateData);
    }

    protected function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->orderDataFactory = $this->createMock(OrderDataFactory::class);
        $this->orderFactory = $this->createMock(OrderFactory::class);
        $this->orderService = new OrderService(
            $this->orderRepository,
            $this->orderDataFactory,
            $this->orderFactory
        );
    }
}
