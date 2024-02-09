<?php

declare(strict_types=1);

namespace Acme\Tests\Domain\WidgetShop\Product;

use Acme\Domain\WidgetShop\Product\Price;
use Acme\Domain\WidgetShop\Product\Product;
use Acme\Domain\WidgetShop\Product\ProductCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Product::class)]
class ProductTest extends TestCase
{
    protected array $record;

    protected function setUp(): void
    {
        $this->record = [
            'name'           => 'Red Widget',
            'code'           => 'R01',
            'price_amount'   => 3295,
            'price_currency' => 'USD',
            'created_at'     => '2024-02-08 13:45:00',
            'modified_at'    => '2024-02-08 13:45:00'
        ];
    }

    public function test_that_name_returns_expected_value(): void
    {
        $product = Product::provision(
            $this->record['name'],
            ProductCode::fromString($this->record['code']),
            Price::fromCents($this->record['price_amount'])
        );

        static::assertSame($this->record['name'], $product->name());
    }

    public function test_that_code_returns_expected_value(): void
    {
        $product = Product::provision(
            $this->record['name'],
            ProductCode::fromString($this->record['code']),
            Price::fromCents($this->record['price_amount'])
        );

        static::assertSame($this->record['code'], $product->code()->toString());
    }

    public function test_that_price_returns_expected_value(): void
    {
        $product = Product::provision(
            $this->record['name'],
            ProductCode::fromString($this->record['code']),
            Price::fromCents($this->record['price_amount'])
        );

        static::assertSame('$32.95', $product->price()->format());
    }

    public function test_that_created_at_returns_expected_value_from_record(): void
    {
        $product = Product::fromRecord($this->record);

        static::assertSame($this->record['created_at'], $product->createdAt()->format('Y-m-d H:i:s'));
    }

    public function test_that_modified_at_returns_expected_value_from_record()
    {
        $product = Product::fromRecord($this->record);

        static::assertSame($this->record['modified_at'], $product->modifiedAt()->format('Y-m-d H:i:s'));
    }

    public function test_that_to_record_returns_same_values_as_original_record(): void
    {
        $product = Product::fromRecord($this->record);

        static::assertSame($this->record, $product->toRecord());
    }
}
