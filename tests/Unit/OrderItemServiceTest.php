<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Factory\OrderItemFactory;
use App\Repository\OrderItemRepository;
use App\Service\OrderItem\OrderItemService;
use PHPUnit\Framework\MockObject\MockObject;

class OrderItemServiceTest extends BaseUnitTest
{
    private OrderItemRepository&MockObject $orderItemRepository;
    private OrderItemFactory&MockObject $orderItemFactory;
    private OrderItemService $orderItemService;

    public function testCreateFromOrderAndItems(): void
    {
        $order = $this->createMock(Order::class);

        $products = $this->getOrderItems();

        $orderItem1 = $this->createMock(OrderItem::class);
        $orderItem2 = $this->createMock(OrderItem::class);

        $this->orderItemFactory
            ->expects($this->exactly(count($products)))
            ->method('createFromOrderAndData')
            ->willReturnOnConsecutiveCalls($orderItem1, $orderItem2);

        $this->orderItemRepository
            ->expects($this->once())
            ->method('saveBulk')
            ->with($this->equalTo([$orderItem1, $orderItem2]));

        $this->orderItemService->createFromOrderAndItems($order, $products);
    }

    protected function setUp(): void
    {
        $this->orderItemRepository = $this->createMock(OrderItemRepository::class);
        $this->orderItemFactory = $this->createMock(OrderItemFactory::class);
        $this->orderItemService = new OrderItemService(
            $this->orderItemRepository,
            $this->orderItemFactory
        );
    }
}
