<?php

declare(strict_types=1);

namespace Acme\Application\Services\Cart;

use Acme\Domain\WidgetShop\Basket\BasketId;
use Acme\Domain\WidgetShop\Product\Price;
use Acme\Domain\WidgetShop\Product\ProductCode;
use Exception;

/**
 * Interface CartService
 */
interface CartService
{
    /**
     * Adds a product to the given basket
     *
     * @throws Exception When an error occurs
     */
    public function add(BasketId $basketId, ProductCode $productCode): void;

    /**
     * Retrieves the total cost of the basket, including delivery
     *
     * @throws Exception When an error occurs
     */
    public function total(BasketId $basketId): Price;
}
