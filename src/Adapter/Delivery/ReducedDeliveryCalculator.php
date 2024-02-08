<?php

declare(strict_types=1);

namespace Acme\Adapter\Delivery;

use Acme\Application\Services\Delivery\DeliveryCalculator;
use Acme\Domain\WidgetShop\Product\Price;

/**
 * Class ReducedDeliveryCalculator
 */
final class ReducedDeliveryCalculator implements DeliveryCalculator
{
    /**
     * @inheritDoc
     */
    public function calculateDeliveryCost(Price $amountSpent): Price
    {
        // orders of $90 or more have free delivery
        if ($amountSpent->amount() >= 9000) {
            return Price::fromCents(0);
        }

        // orders of $50 - $90 pay $2.95 for delivery
        if ($amountSpent->amount() >= 5000) {
            return Price::fromCents(295);
        }

        // orders under $50 pay $4.95 for delivery
        return Price::fromCents(495);
    }
}
