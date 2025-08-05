<?php

declare(strict_types=1);

namespace App\DTO\Response;

readonly class OrderCreatedResponse implements \JsonSerializable
{
    public function __construct(
        public int $id,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
