<?php

declare(strict_types=1);

namespace Acme\Domain\WidgetShop\Product;

use Exception;

/**
 * Interface ProductRepository
 */
interface ProductRepository
{
    /**
     * Retrieves product by product code
     *
     * @throws Exception When an error occurs
     */
    public function getByProductCode(ProductCode $code): ?Product;
}
