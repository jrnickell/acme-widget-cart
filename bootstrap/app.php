<?php

declare(strict_types=1);

use Acme\Adapter\Delivery\ReducedDeliveryCalculator;
use Acme\Adapter\Offers\BogoHalfRedWidget;
use Acme\Adapter\Repository\InMemoryBasketRepository;
use Acme\Adapter\Repository\StaticProductRepository;
use Acme\Adapter\Service\ServiceContainer;
use Acme\Application\Services\Cart\CartService;
use Acme\Application\Services\Delivery\DeliveryCalculator;
use Acme\Application\Services\Offers\SpecialOfferCartService;
use Acme\Domain\WidgetShop\Basket\BasketRepository;
use Acme\Domain\WidgetShop\Product\ProductRepository;

$autoload = sprintf('%s/vendor/autoload.php', dirname(__DIR__));

if (!file_exists($autoload)) {
    throw new RuntimeException('Composer install required');
}

require_once $autoload;

$container = new ServiceContainer();

$container->set(ProductRepository::class, function () {
    return new StaticProductRepository();
});

$container->set(BasketRepository::class, function () {
    return new InMemoryBasketRepository();
});

$container->set(DeliveryCalculator::class, function () {
    return new ReducedDeliveryCalculator();
});

$container->set(BogoHalfRedWidget::class, function (ServiceContainer $container) {
    return new BogoHalfRedWidget(
        $container->get(BasketRepository::class)
    );
});

$container->set(CartService::class, function (ServiceContainer $container) {
    $specialOfferCartService = new SpecialOfferCartService(
        $container->get(BasketRepository::class),
        $container->get(ProductRepository::class),
        $container->get(DeliveryCalculator::class)
    );

    // adding new special offers
    $specialOfferCartService->addOffer($container->get(BogoHalfRedWidget::class));

    return $specialOfferCartService;
});

return $container;
