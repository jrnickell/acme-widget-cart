<?php

declare(strict_types=1);

namespace Acme\Tests\Domain\WidgetShop\Product;

use Acme\Domain\WidgetShop\Product\ProductCode;
use DomainException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ProductCode::class)]
class ProductCodeTest extends TestCase
{
    public function test_that_string_cast_returns_expected_value(): void
    {
        $code = 'R01';
        $productCode = ProductCode::fromString($code);

        static::assertSame($code, (string) $productCode);
    }

    public function test_that_from_string_throws_exception_for_invalid_code(): void
    {
        static::expectException(DomainException::class);

        ProductCode::fromString('r01');
    }
}
