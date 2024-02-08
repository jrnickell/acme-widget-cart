<?php

declare(strict_types=1);

namespace Acme\Tests\Domain\WidgetShop\Basket;

use Acme\Domain\WidgetShop\Basket\BasketId;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BasketId::class)]
class BasketIdTest extends TestCase
{
    public function test_that_string_cast_returns_expected_value()
    {
        $uuidString = '018d8a66-9d2a-73e6-9631-661f4c4a46a6';
        $basketId = BasketId::fromString($uuidString);

        static::assertSame($uuidString, (string) $basketId);
    }

    public function test_that_generate_returns_uuid_v7()
    {
        $basketId = BasketId::generate();

        static::assertSame('7', substr($basketId->toString(), 14, 1));
    }
}
