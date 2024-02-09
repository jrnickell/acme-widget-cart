<?php

declare(strict_types=1);

namespace Acme\Tests\Adapter;

use Acme\Adapter\Delivery\ReducedDeliveryCalculator;
use Acme\Adapter\Offers\BogoHalfRedWidget;
use Acme\Adapter\Repository\InMemoryBasketRepository;
use Acme\Adapter\Repository\StaticProductRepository;
use Acme\Adapter\Service\ServiceContainer;
use Acme\Application\Service\Cart\CartService;
use Acme\Application\Service\Offers\SpecialOfferCartService;
use Acme\Domain\WidgetShop\Basket\BasketId;
use Acme\Domain\WidgetShop\Product\ProductCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

#[CoversClass(SpecialOfferCartService::class)]
#[CoversClass(ReducedDeliveryCalculator::class)]
#[CoversClass(BogoHalfRedWidget::class)]
#[CoversClass(InMemoryBasketRepository::class)]
#[CoversClass(StaticProductRepository::class)]
#[CoversClass(ServiceContainer::class)]
class IntegrationTest extends TestCase
{
    protected ContainerInterface $container;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->container = require sprintf('%s/bootstrap/app.php', dirname(dirname(__DIR__)));
    }

    public function test_that_the_example_baskets_return_expected_values_set_1()
    {
        /** @var CartService $cartService */
        $cartService = $this->container->get(CartService::class);

        $basketId = BasketId::generate();

        $cartService->add($basketId, ProductCode::fromString('B01'));
        $cartService->add($basketId, ProductCode::fromString('G01'));

        static::assertSame('$37.85', $cartService->total($basketId)->format());
    }

    public function test_that_the_example_baskets_return_expected_values_set_2()
    {
        /** @var CartService $cartService */
        $cartService = $this->container->get(CartService::class);

        $basketId = BasketId::generate();

        $cartService->add($basketId, ProductCode::fromString('R01'));
        $cartService->add($basketId, ProductCode::fromString('R01'));

        static::assertSame('$54.37', $cartService->total($basketId)->format());
    }

    public function test_that_the_example_baskets_return_expected_values_set_3()
    {
        /** @var CartService $cartService */
        $cartService = $this->container->get(CartService::class);

        $basketId = BasketId::generate();

        $cartService->add($basketId, ProductCode::fromString('R01'));
        $cartService->add($basketId, ProductCode::fromString('G01'));

        static::assertSame('$60.85', $cartService->total($basketId)->format());
    }

    public function test_that_the_example_baskets_return_expected_values_set_4()
    {
        /** @var CartService $cartService */
        $cartService = $this->container->get(CartService::class);

        $basketId = BasketId::generate();

        $cartService->add($basketId, ProductCode::fromString('B01'));
        $cartService->add($basketId, ProductCode::fromString('B01'));
        $cartService->add($basketId, ProductCode::fromString('R01'));
        $cartService->add($basketId, ProductCode::fromString('R01'));
        $cartService->add($basketId, ProductCode::fromString('R01'));

        static::assertSame('$98.27', $cartService->total($basketId)->format());
    }
}
