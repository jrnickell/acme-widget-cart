<?php

declare(strict_types=1);

$autoload = sprintf('%s/vendor/autoload.php', dirname(__DIR__));

if (!file_exists($autoload)) {
    throw new RuntimeException('Composer install required');
}

require_once $autoload;
