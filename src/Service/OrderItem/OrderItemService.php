<?php

declare(strict_types=1);

namespace App\Service\OrderItem;

use App\Entity\Order;
use App\Factory\OrderItemFactory;
use App\Repository\OrderItemRepository;

readonly class OrderItemService implements OrderItemServiceInterface
{
    public function __construct(
        private OrderItemRepository $orderItemRepository,
        private OrderItemFactory $orderItemFactory,
    ) {
    }

    public function createFromOrderAndItems(Order $order, array $products): void
    {
        $orderItems = [];

        foreach ($products as $orderItemData) {
            $orderItems[] = $this->orderItemFactory->createFromOrderAndData($order, $orderItemData);
        }

        $this->orderItemRepository->saveBulk($orderItems);
    }
}
