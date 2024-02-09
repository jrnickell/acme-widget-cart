<?php

declare(strict_types=1);

namespace Acme\Tests\Application\Service\Offers;

use Acme\Application\Service\Delivery\DeliveryCalculator;
use Acme\Application\Service\Offers\SpecialOffer;
use Acme\Application\Service\Offers\SpecialOfferCartService;
use Acme\Domain\WidgetShop\Basket\Basket;
use Acme\Domain\WidgetShop\Basket\BasketId;
use Acme\Domain\WidgetShop\Basket\BasketRepository;
use Acme\Domain\WidgetShop\Product\Price;
use Acme\Domain\WidgetShop\Product\Product;
use Acme\Domain\WidgetShop\Product\ProductCode;
use Acme\Domain\WidgetShop\Product\ProductRepository;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ReflectionObject;
use RuntimeException;

#[CoversClass(SpecialOfferCartService::class)]
class SpecialOfferCartServiceTest extends TestCase
{
    /** @var SpecialOfferCartService */
    protected $cartService;
    /** @var BasketRepository|MockInterface  */
    protected $mockBasketRepository;
    /** @var ProductRepository|MockInterface  */
    protected $mockProductRepository;
    /** @var DeliveryCalculator|MockInterface */
    protected $mockDeliveryCalculator;
    /** @var SpecialOffer|MockInterface */
    protected $mockSpecialOffer;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->mockBasketRepository = Mockery::mock(BasketRepository::class);
        $this->mockProductRepository = Mockery::mock(ProductRepository::class);
        $this->mockDeliveryCalculator = Mockery::mock(DeliveryCalculator::class);
        $this->mockSpecialOffer = Mockery::mock(SpecialOffer::class);
        $this->cartService = new SpecialOfferCartService(
            $this->mockBasketRepository,
            $this->mockProductRepository,
            $this->mockDeliveryCalculator
        );
        $this->cartService->addOffer($this->mockSpecialOffer);
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $reflection = new ReflectionObject($this);

        foreach ($reflection->getProperties() as $prop) {
            if (
                !$prop->isStatic()
                && str_starts_with($prop->getDeclaringClass()->getName(), 'PHPUnit')
            ) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }

        $container = Mockery::getContainer();

        if ($container) {
            $this->addToAssertionCount(
                $container->mockery_getExpectationCount()
            );
        }

        Mockery::close();
    }

    public function test_that_offers_are_correctly_discounted_from_total(): void
    {
        $basketId = BasketId::generate();
        $basket = Basket::provision($basketId);
        $b01 = ProductCode::fromString('B01');
        $g01 = ProductCode::fromString('G01');
        $blueWidget = Product::provision('Blue Widget', $b01, Price::fromCents(795));
        $greenWidget = Product::provision('Green Widget', $g01, Price::fromCents(2495));

        $this->mockBasketRepository
            ->shouldReceive('getById')
            ->times(3)
            ->with($basketId)
            ->andReturn($basket);

        $this->mockProductRepository
            ->shouldReceive('getByProductCode')
            ->once()
            ->with($b01)
            ->andReturn($blueWidget);

        $this->mockProductRepository
            ->shouldReceive('getByProductCode')
            ->once()
            ->with($g01)
            ->andReturn($greenWidget);

        $this->mockSpecialOffer
            ->shouldReceive('providesDiscount')
            ->once()
            ->with($basketId)
            ->andReturn(Price::fromCents(0));

        $this->mockDeliveryCalculator
            ->shouldReceive('calculateDeliveryCost')
            ->once()
            ->with(Price::class)
            ->andReturn(Price::fromCents(495));

        $this->cartService->add($basketId, $b01);
        $this->cartService->add($basketId, $g01);

        $price = $this->cartService->total($basketId);

        static::assertSame('$37.85', $price->format());
    }

    public function test_that_add_will_provision_basket_when_not_found()
    {
        $basketId = BasketId::generate();
        $basket = Basket::provision($basketId);
        $b01 = ProductCode::fromString('B01');
        $blueWidget = Product::provision('Blue Widget', $b01, Price::fromCents(795));

        // manually adding product for testing
        $basket->addProduct($blueWidget);

        $this->mockBasketRepository
            ->shouldReceive('getById')
            ->twice()
            ->with($basketId)
            ->andReturn(null, $basket);

        $this->mockBasketRepository
            ->shouldReceive('add')
            ->once()
            ->with(Basket::class)
            ->andReturn(null);

        $this->mockProductRepository
            ->shouldReceive('getByProductCode')
            ->once()
            ->with($b01)
            ->andReturn($blueWidget);

        $this->mockSpecialOffer
            ->shouldReceive('providesDiscount')
            ->once()
            ->with($basketId)
            ->andReturn(Price::fromCents(0));

        $this->mockDeliveryCalculator
            ->shouldReceive('calculateDeliveryCost')
            ->once()
            ->with(Price::class)
            ->andReturn(Price::fromCents(495));

        $this->cartService->add($basketId, $b01);

        $price = $this->cartService->total($basketId);

        static::assertSame('$12.90', $price->format());
    }

    public function test_that_total_will_provision_basket_when_not_found()
    {
        $basketId = BasketId::generate();

        $this->mockBasketRepository
            ->shouldReceive('getById')
            ->once()
            ->with($basketId)
            ->andReturn(null);

        $this->mockBasketRepository
            ->shouldReceive('add')
            ->once()
            ->with(Basket::class)
            ->andReturn(null);

        $this->mockSpecialOffer
            ->shouldReceive('providesDiscount')
            ->once()
            ->with($basketId)
            ->andReturn(Price::fromCents(0));

        $this->mockDeliveryCalculator
            ->shouldReceive('calculateDeliveryCost')
            ->once()
            ->with(Price::class)
            ->andReturn(Price::fromCents(495));

        $price = $this->cartService->total($basketId);

        static::assertSame('$4.95', $price->format());
    }

    public function test_that_total_throws_exception_when_discount_exceeds_total()
    {
        static::expectException(RuntimeException::class);

        $basketId = BasketId::generate();

        $this->mockBasketRepository
            ->shouldReceive('getById')
            ->once()
            ->with($basketId)
            ->andReturn(null);

        $this->mockBasketRepository
            ->shouldReceive('add')
            ->once()
            ->with(Basket::class)
            ->andReturn(null);

        $this->mockSpecialOffer
            ->shouldReceive('providesDiscount')
            ->once()
            ->with($basketId)
            ->andReturn(Price::fromCents(1000));

        $this->cartService->total($basketId);
    }
}
