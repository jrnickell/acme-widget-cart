<?php

declare(strict_types=1);

namespace Acme\Domain\WidgetShop\Product;

use DomainException;
use NumberFormatter;

/**
 * Class Price
 */
final class Price
{
    /**
     * Constructs Price
     *
     * @internal
     */
    protected function __construct(
        protected int $amount,
        protected string $currency = 'USD'
    ) {
    }

    /**
     * Creates instance from price in cents (USD)
     */
    public static function fromCents(int $amount): static
    {
        if ($amount < 0) {
            throw new DomainException('Price amount cannot be negative');
        }

        return new static($amount);
    }

    /**
     * Retrieves the amount
     */
    public function amount(): int
    {
        return $this->amount;
    }

    /**
     * Retrieves the currency
     */
    public function currency(): string
    {
        return $this->currency;
    }

    /**
     * Retrieves the price as a formatted string
     */
    public function format(string $locale = 'en_US'): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $float = round($this->amount / 100, 2);

        return $formatter->formatCurrency($float, $this->currency);
    }
}
