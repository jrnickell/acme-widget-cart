<?php

declare(strict_types=1);

namespace Acme\Domain\WidgetShop\Product;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Class Product
 */
final class Product
{
    /**
     * Constructs Product
     *
     * @internal
     */
    protected function __construct(
        protected string $name,
        protected ProductCode $code,
        protected Price $price,
        protected DateTimeImmutable $createdAt,
        protected DateTimeImmutable $modifiedAt
    ) {
    }

    /**
     * Provides a new product instance
     */
    public static function provision(string $name, ProductCode $code, Price $price): static
    {
        $createdAt = new DateTimeImmutable('now');
        $modifiedAt = new DateTimeImmutable('now');

        return new static($name, $code, $price, $createdAt, $modifiedAt);
    }

    /**
     * Creates instance from data record
     */
    public static function fromRecord(array $record): static
    {
        $name = $record['name'];
        $code = ProductCode::fromString($record['code']);
        $price = Price::fromCents($record['price_amount']);
        $createdAtParts = explode(' ', $record['created_at'], 2);
        $createdAt = DateTimeImmutable::createFromFormat(
            DateTimeInterface::ATOM,
            sprintf('%sT%s+00:00', $createdAtParts[0], $createdAtParts[1])
        );
        $modifiedAtParts = explode(' ', $record['modified_at'], 2);
        $modifiedAt = DateTimeImmutable::createFromFormat(
            DateTimeInterface::ATOM,
            sprintf('%sT%s+00:00', $modifiedAtParts[0], $modifiedAtParts[1])
        );

        return new static($name, $code, $price, $createdAt, $modifiedAt);
    }

    /**
     * Retrieves the name
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Retrieves the code
     */
    public function code(): ProductCode
    {
        return $this->code;
    }

    /**
     * Retrieves the price
     */
    public function price(): Price
    {
        return $this->price;
    }

    /**
     * Retrieves the created date/time
     */
    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Retrieves the last modified date/time
     */
    public function modifiedAt(): DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    /**
     * Retrieves data record
     *
     * Not generally needed when using an ORM.
     *
     * This provides scalar values from the entity for storage.
     */
    public function toRecord(): array
    {
        return [
            'name'           => $this->name,
            'code'           => $this->code->toString(),
            'price_amount'   => $this->price->amount(),
            'price_currency' => $this->price->currency(),
            'created_at'     => $this->createdAt->format('Y-m-d H:i:s'),
            'modified_at'    => $this->modifiedAt->format('Y-m-d H:i:s')
        ];
    }
}
