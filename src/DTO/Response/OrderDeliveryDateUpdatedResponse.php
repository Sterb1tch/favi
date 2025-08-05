<?php

declare(strict_types=1);

namespace App\DTO\Response;

use App\Entity\Order;

readonly class OrderDeliveryDateUpdatedResponse implements \JsonSerializable
{
    public function __construct(
        public Order $order,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->order->getId(),
            'newExpectedDeliveryDate' => $this->order->getExpectedDeliveryDate()->format('Y-m-d H:i:s'),
        ];
    }
}
