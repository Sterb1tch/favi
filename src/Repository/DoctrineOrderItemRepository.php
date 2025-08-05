<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\OrderItem;

readonly class DoctrineOrderItemRepository extends DoctrineRepository implements OrderItemRepository
{
    public function save(OrderItem $orderItem): void
    {
        $this->entityManager->persist($orderItem);
        $this->entityManager->flush();
    }

    public function saveBulk(array $orderItems): void
    {
        foreach ($orderItems as $orderItem) {
            $this->entityManager->persist($orderItem);
        }

        $this->entityManager->flush();
    }
}
