<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\DTO\OrderData;
use App\DTO\Response\OrderCreatedResponse;
use App\DTO\Response\OrderDeliveryDateUpdatedResponse;
use App\DTO\UpdateOrderDeliveryDateData;
use App\Entity\Order;
use App\Facade\Order\OrderFacade;
use App\Service\Order\OrderServiceInterface;
use App\Service\OrderItem\OrderItemServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;

class OrderFacadeTest extends BaseUnitTest
{
    private OrderServiceInterface&MockObject $orderService;
    private OrderItemServiceInterface&MockObject $orderItemService;
    private OrderFacade $orderFacade;

    public function testCreateOrder(): void
    {
        $orderData = new OrderData();
        $orderData->products = $this->getOrderItems();

        $order = $this->createMock(Order::class);
        $order
            ->expects($this->once())
            ->method('getId')
            ->willReturn(123);

        $this->orderService
            ->expects($this->once())
            ->method('createOrder')
            ->with($this->equalTo($orderData))
            ->willReturn($order);

        $this->orderItemService
            ->expects($this->once())
            ->method('createFromOrderAndItems')
            ->with(
                $this->equalTo($order),
                $this->equalTo($orderData->products)
            );

        $result = $this->orderFacade->createOrder($orderData);

        self::assertInstanceOf(OrderCreatedResponse::class, $result);
        self::assertEquals(123, $result->getId());
    }

    public function testUpdateDeliveryDate(): void
    {
        $orderId = 123;
        $deliveryDateData = new UpdateOrderDeliveryDateData();
        $deliveryDateData->expectedDeliveryDate = new \DateTime('2025-12-01');

        $order = $this->createMock(Order::class);

        $this->orderService
            ->expects($this->once())
            ->method('updateExpectedDeliveryDate')
            ->with(
                $this->equalTo($orderId),
                $this->equalTo($deliveryDateData)
            )
            ->willReturn($order);

        $result = $this->orderFacade->updateDeliveryDate($orderId, $deliveryDateData);

        self::assertInstanceOf(OrderDeliveryDateUpdatedResponse::class, $result);
        self::assertSame($order, $result->getOrder());
    }

    protected function setUp(): void
    {
        $this->orderService = $this->createMock(OrderServiceInterface::class);
        $this->orderItemService = $this->createMock(OrderItemServiceInterface::class);

        $this->orderFacade = new OrderFacade(
            $this->orderService,
            $this->orderItemService
        );
    }
}
