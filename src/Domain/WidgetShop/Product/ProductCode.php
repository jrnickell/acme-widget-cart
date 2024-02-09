<?php

declare(strict_types=1);

namespace Acme\Domain\WidgetShop\Product;

use DomainException;

/**
 * Class ProductCode
 */
final class ProductCode
{
    /**
     * Constructs ProductCode
     *
     * @internal
     *
     * @throws DomainException When code is invalid
     */
    protected function __construct(protected string $code)
    {
        // TODO: update validations to match expected product code formatting
        if (!preg_match('/^[A-Z0-9]+$/', $this->code)) {
            throw new DomainException('Product code expected to be uppercase letters and numbers');
        }
    }

    /**
     * Creates instance from a product code
     *
     * @throws DomainException When code is invalid
     */
    public static function fromString(string $code): static
    {
        return new static($code);
    }

    /**
     * Retrieves a string representation
     */
    public function toString(): string
    {
        return $this->code;
    }

    /**
     * Retrieves a string representation
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
