<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class OrderData implements \JsonSerializable
{
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public string $orderId;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public string $partnerId;

    #[Assert\NotNull]
    #[Assert\Type(\DateTime::class)]
    public \DateTime $expectedDeliveryDate;

    #[Assert\NotNull]
    #[Assert\GreaterThan(value: 0)]
    public float $value;

    /** @var OrderItemData[] */
    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    public array $products;

    public function jsonSerialize(): array
    {
        return [
            'orderId' => $this->orderId,
            'partnerId' => $this->partnerId,
            'expectedDeliveryDate' => $this->expectedDeliveryDate,
            'value' => $this->value,
            'products' => $this->products,
        ];
    }
}
