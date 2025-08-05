<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use App\Exception\OrderNotFoundException;
use Doctrine\ORM\NoResultException;

readonly class DoctrineOrderRepository extends DoctrineRepository implements OrderRepository
{
    public function save(Order $order): void
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    public function findOrderByOrderId(string $orderId): ?Order
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('o')
            ->from(Order::class, 'o')
            ->where('o.orderId = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getById(int $id): Order
    {
        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('o')
                ->from(Order::class, 'o')
                ->where('o.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException) {
            throw new OrderNotFoundException(sprintf('Order with ID %d not found', $id));
        }
    }
}
