<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

if (filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN)) {
    umask(0000);
}
