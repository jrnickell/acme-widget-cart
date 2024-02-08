<?php

declare(strict_types=1);

namespace Acme\Tests\Domain\WidgetShop\Basket;

use Acme\Domain\WidgetShop\Basket\Basket;
use Acme\Domain\WidgetShop\Product\Price;
use Acme\Domain\WidgetShop\Product\Product;
use Acme\Domain\WidgetShop\Product\ProductCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Basket::class)]
class BasketTest extends TestCase
{
    protected string $reg = '/\A([a-f0-9]{8})-([a-f0-9]{4})-([a-f0-9]{4})-([a-f0-9]{2})([a-f0-9]{2})-([a-f0-9]{12})\z/';

    public function test_that_id_returns_expected_value()
    {
        $basket = Basket::provision();

        static::assertTrue(!!preg_match($this->reg, $basket->id()->toString()));
    }

    public function test_that_get_products_returns_expected_value()
    {
        $productRed = Product::provision('Red Widget', ProductCode::fromString('R01'), Price::fromCents(1000));
        $productGreen = Product::provision('Green Widget', ProductCode::fromString('G01'), Price::fromCents(500));
        $productBlue = Product::provision('Blue Widget', ProductCode::fromString('B01'), Price::fromCents(250));

        $basket = Basket::provision();

        $basket->addProduct($productRed);
        $basket->addProduct($productGreen);
        $basket->addProduct($productBlue);

        static::assertCount(3, $basket->getProducts());
    }

    public function test_that_get_total_price_returns_expected_value()
    {
        $productRed = Product::provision('Red Widget', ProductCode::fromString('R01'), Price::fromCents(1000));
        $productGreen = Product::provision('Green Widget', ProductCode::fromString('G01'), Price::fromCents(500));
        $productBlue = Product::provision('Blue Widget', ProductCode::fromString('B01'), Price::fromCents(250));

        $basket = Basket::provision();

        $basket->addProduct($productRed);
        $basket->addProduct($productGreen);
        $basket->addProduct($productBlue);

        static::assertSame('$17.50', $basket->getTotalPrice()->format());
    }
}
