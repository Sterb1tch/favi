<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;

abstract readonly class DoctrineRepository
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }
}
