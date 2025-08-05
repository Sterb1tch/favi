<?php

declare(strict_types=1);

namespace App\Exception;

use Doctrine\ORM\UnexpectedResultException;

class OrderNotFoundException extends UnexpectedResultException
{
}
