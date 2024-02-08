<?php

declare(strict_types=1);

namespace Acme\Domain\WidgetShop\Basket;

use DomainException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class BasketId
 */
final class BasketId
{
    protected UuidInterface $uuid;

    /**
     * Constructs BasketId
     *
     * @internal
     */
    protected function __construct(string $uuid)
    {
        $this->uuid = Uuid::fromString($uuid);
    }

    /**
     * Generates a new instance
     */
    public static function generate(): static
    {
        return new static(Uuid::uuid7()->toString());
    }

    /**
     * Creates instance from a UUID
     *
     * @throws DomainException When UUID is invalid
     */
    public static function fromString(string $uuid): static
    {
        return new static($uuid);
    }

    /**
     * Retrieves a string representation
     */
    public function toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * Retrieves a string representation
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
