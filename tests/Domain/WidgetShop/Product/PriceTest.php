<?php

declare(strict_types=1);

namespace Acme\Tests\Domain\WidgetShop\Product;

use Acme\Domain\WidgetShop\Product\Price;
use DomainException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Price::class)]
class PriceTest extends TestCase
{
    public function test_that_amount_returns_expected_value()
    {
        $amount = 2743;
        $price = Price::fromCents($amount);

        static::assertSame($amount, $price->amount());
    }

    public function test_that_currency_returns_expected_value()
    {
        $amount = 2743;
        $price = Price::fromCents($amount);

        static::assertSame('USD', $price->currency());
    }

    public function test_that_format_returns_expected_value()
    {
        $amount = 2743;
        $price = Price::fromCents($amount);

        static::assertSame('$27.43', $price->format());
    }

    public function test_that_from_cents_throws_exception_for_negative_amount()
    {
        static::expectException(DomainException::class);

        Price::fromCents(-1250);
    }
}
