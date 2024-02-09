<?php

declare(strict_types=1);

namespace Acme\Application\Service\Offers;

use Acme\Domain\WidgetShop\Basket\BasketId;
use Acme\Domain\WidgetShop\Product\Price;

/**
 * Interface SpecialOffer
 */
interface SpecialOffer
{
    /**
     * Retrieves a discount if applicable
     */
    public function providesDiscount(BasketId $basketId): ?Price;
}
