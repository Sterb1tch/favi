<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class OrderAlreadyExistsException extends ConflictHttpException
{
}
