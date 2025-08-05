<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class OrderItemData implements \JsonSerializable
{
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public string $productId;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public float $price;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    public int $quantity;

    public function jsonSerialize(): array
    {
        return [
            'productId' => $this->productId,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ];
    }
}
