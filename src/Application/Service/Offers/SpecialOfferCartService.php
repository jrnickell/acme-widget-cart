<?php

declare(strict_types=1);

namespace Acme\Application\Service\Offers;

use Acme\Application\Service\Cart\CartService;
use Acme\Application\Service\Delivery\DeliveryCalculator;
use Acme\Domain\WidgetShop\Basket\Basket;
use Acme\Domain\WidgetShop\Basket\BasketId;
use Acme\Domain\WidgetShop\Basket\BasketRepository;
use Acme\Domain\WidgetShop\Product\Price;
use Acme\Domain\WidgetShop\Product\ProductCode;
use Acme\Domain\WidgetShop\Product\ProductRepository;
use RuntimeException;

/**
 * Class SpecialOfferCartService
 */
final class SpecialOfferCartService implements CartService
{
    protected array $offers = [];

    /**
     * Constructs SpecialOfferCartService
     */
    public function __construct(
        protected BasketRepository $basketRepository,
        protected ProductRepository $productRepository,
        protected DeliveryCalculator $deliveryCalculator
    ) {
    }

    /**
     * Adds a special offer to the calculation
     */
    public function addOffer(SpecialOffer $offer): void
    {
        $this->offers[] = $offer;
    }

    /**
     * @inheritDoc
     */
    public function add(BasketId $basketId, ProductCode $productCode): void
    {
        $basket = $this->basketRepository->getById($basketId);

        if ($basket === null) {
            $basket = Basket::provision($basketId);
            $this->basketRepository->add($basket);
        }

        $product = $this->productRepository->getByProductCode($productCode);

        $basket->addProduct($product);
    }

    /**
     * @inheritDoc
     */
    public function total(BasketId $basketId): Price
    {
        $basket = $this->basketRepository->getById($basketId);

        if ($basket === null) {
            $basket = Basket::provision($basketId);
            $this->basketRepository->add($basket);
        }

        $price = $basket->getTotalPrice();

        /** @var SpecialOffer $offer */
        foreach ($this->offers as $offer) {
            $discount = $offer->providesDiscount($basketId);
            if ($discount !== null) {
                if ($discount->amount() > $price->amount()) {
                    throw new RuntimeException('Discount cannot be greater than the price');
                }

                $price = Price::fromCents($price->amount() - $discount->amount());
            }
        }

        $delivery = $this->deliveryCalculator->calculateDeliveryCost($price);

        return Price::fromCents($price->amount() + $delivery->amount());
    }
}
