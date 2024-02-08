<?php

declare(strict_types=1);

namespace Acme\Domain\WidgetShop\Basket;

use Exception;

/**
 * Interface BasketRepository
 */
interface BasketRepository
{
    /**
     * Retrieves a basket by ID
     *
     * @throws Exception When an error occurs
     */
    public function getById(BasketId $id): ?Basket;

    /**
     * Adds a basket
     *
     * @throws Exception When an error occurs
     */
    public function add(Basket $basket): void;
}
