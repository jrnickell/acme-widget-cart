<?php

declare(strict_types=1);

namespace Acme\Adapter\Repository;

use Acme\Domain\WidgetShop\Basket\Basket;
use Acme\Domain\WidgetShop\Basket\BasketId;
use Acme\Domain\WidgetShop\Basket\BasketRepository;

/**
 * Class InMemoryBasketRepository
 */
final class InMemoryBasketRepository implements BasketRepository
{
    protected array $baskets = [];

    /**
     * @inheritDoc
     */
    public function getById(BasketId $id): ?Basket
    {
        $key = $id->toString();
        if (!isset($this->baskets[$key])) {
            return null;
        }

        return $this->baskets[$key];
    }

    /**
     * @inheritDoc
     */
    public function add(Basket $basket): void
    {
        $key = $basket->id()->toString();
        $this->baskets[$key] = $basket;
    }
}
