<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\DTO\OrderItemData;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;

#[DoesNotPerformAssertions]
class BaseUnitTest extends TestCase
{
    public function getOrderItems(): array
    {
        $orderItemDataOne = new OrderItemData();
        $orderItemDataOne->productId = '123';
        $orderItemDataOne->quantity = 2;
        $orderItemDataOne->price = 50.0;

        $orderItemDataTwo = new OrderItemData();
        $orderItemDataTwo->productId = '456';
        $orderItemDataTwo->quantity = 1;
        $orderItemDataTwo->price = 100.0;

        return [$orderItemDataOne, $orderItemDataTwo];
    }
}
