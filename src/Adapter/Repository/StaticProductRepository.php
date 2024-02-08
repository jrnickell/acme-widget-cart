<?php

declare(strict_types=1);

namespace Acme\Adapter\Repository;

use Acme\Domain\WidgetShop\Product\Price;
use Acme\Domain\WidgetShop\Product\Product;
use Acme\Domain\WidgetShop\Product\ProductCode;
use Acme\Domain\WidgetShop\Product\ProductRepository;

/**
 * Class StaticProductRepository
 */
final class StaticProductRepository implements ProductRepository
{
    protected array $products;

    /**
     * Constructs StaticProductRepository
     */
    public function __construct()
    {
        $this->products = [
            'R01' => Product::provision('Red Widget', ProductCode::fromString('R01'), Price::fromCents(3295)),
            'G01' => Product::provision('Green Widget', ProductCode::fromString('G01'), Price::fromCents(2495)),
            'B01' => Product::provision('Blue Widget', ProductCode::fromString('B01'), Price::fromCents(795))
        ];
    }

    /**
     * @inheritDoc
     */
    public function getByProductCode(ProductCode $code): ?Product
    {
        $key = $code->toString();
        if (!isset($this->products[$key])) {
            return null;
        }

        return $this->products[$key];
    }
}
