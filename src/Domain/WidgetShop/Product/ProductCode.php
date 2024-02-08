<?php

declare(strict_types=1);

namespace Acme\Domain\WidgetShop\Product;

use DomainException;

/**
 * Class ProductCode
 */
final class ProductCode
{
    // TODO: replace with a schema to validate future codes
    protected const VALID_CODES = ['R01', 'G01', 'B01'];

    /**
     * Constructs ProductCode
     *
     * @internal
     *
     * @throws DomainException When code is invalid
     */
    protected function __construct(protected string $code)
    {
        if (!in_array($this->code, static::VALID_CODES)) {
            throw new DomainException(sprintf('Invalid product code: %s', $this->code));
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
