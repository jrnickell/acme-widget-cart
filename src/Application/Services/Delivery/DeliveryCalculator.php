<?php

declare(strict_types=1);

namespace Acme\Application\Services\Delivery;

use Acme\Domain\WidgetShop\Product\Price;

/**
 * Interface DeliveryCalculator
 */
interface DeliveryCalculator
{
    /**
     * Calculates delivery cost for the given products
     */
    public function calculateDeliveryCost(Price $amountSpent): Price;
}
