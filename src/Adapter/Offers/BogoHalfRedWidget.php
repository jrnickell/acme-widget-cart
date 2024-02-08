<?php

declare(strict_types=1);

namespace Acme\Adapter\Offers;

use Acme\Application\Services\Offers\SpecialOffer;
use Acme\Domain\WidgetShop\Basket\BasketId;
use Acme\Domain\WidgetShop\Basket\BasketRepository;
use Acme\Domain\WidgetShop\Product\Price;
use Acme\Domain\WidgetShop\Product\Product;

/**
 * Class BogoHalfRedWidget
 */
class BogoHalfRedWidget implements SpecialOffer
{
    /**
     * Constructs BogoHalfRedWidget
     */
    public function __construct(protected BasketRepository $basketRepository)
    {
    }

    /**
     * Checks for any provided discounts
     */
    public function providesDiscount(BasketId $basketId): ?Price
    {
        $basket = $this->basketRepository->getById($basketId);

        if ($basket === null) {
            return null;
        }

        $discount = Price::fromCents(0);
        $discountPerPair = 0;
        $redWidgetCount = 0;
        /** @var Product $product */
        foreach ($basket->getProducts() as $product) {
            if ($product->code()->toString() === 'R01') {
                $discountPerPair = Price::fromCents((int) round($product->price()->amount() / 2));
                $redWidgetCount++;
            }
        }

        if ($redWidgetCount > 1) {
            $pairs = (int) round($redWidgetCount / 2, 0, PHP_ROUND_HALF_DOWN);
            $discount = Price::fromCents($discountPerPair->amount() * $pairs);
        }

        return $discount;
    }
}
