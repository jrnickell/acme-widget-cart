<?php

declare(strict_types=1);

namespace Acme\Domain\WidgetShop\Basket;

use Acme\Domain\WidgetShop\Product\Price;
use Acme\Domain\WidgetShop\Product\Product;

/**
 * Class Basket
 */
final class Basket
{
    protected array $products = [];

    /**
     * Constructs Basket
     */
    protected function __construct(protected BasketId $id)
    {
    }

    /**
     * Provides a new basket instance
     */
    public static function provision(?BasketId $id = null): static
    {
        if ($id === null) {
            $id = BasketId::generate();
        }

        return new static($id);
    }

    /**
     * Retrieves the ID
     */
    public function id(): BasketId
    {
        return $this->id;
    }

    /**
     * Adds a product to the basket
     */
    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

    /**
     * Retrieves the products
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Retrieves the total price of products in the basket
     */
    public function getTotalPrice(): Price
    {
        $totalCents = 0;

        /** @var Product $product */
        foreach ($this->products as $product) {
            $totalCents += $product->price()->amount();
        }

        return Price::fromCents($totalCents);
    }
}
