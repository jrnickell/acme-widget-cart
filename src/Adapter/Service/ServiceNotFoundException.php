<?php

declare(strict_types=1);

namespace Acme\Adapter\Service;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class ServiceNotFoundException
 */
final class ServiceNotFoundException extends Exception implements NotFoundExceptionInterface
{
}
