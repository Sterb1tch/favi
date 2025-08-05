<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateOrderDeliveryDateData implements \JsonSerializable
{
    #[Assert\NotNull]
    #[Assert\Type(\DateTime::class)]
    public \DateTime $expectedDeliveryDate;

    public function jsonSerialize(): array
    {
        return [
            'expectedDeliveryDate' => $this->expectedDeliveryDate,
        ];
    }
}
