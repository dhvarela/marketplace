<?php
declare(strict_types=1);

namespace App\Marketplace\Domain\CartLine;

use App\Marketplace\Domain\Product\ProductInterface;

class CartLine
{
    const MAX_PRODUCT_UNITS = 50;

    /** @var ProductInterface */
    private $product;
    /** @var int */
    private $quantity;

    public function __construct(ProductInterface $product, int $quantity)
    {
        $this->ensureMaxProductUnitsNotReached($quantity);

        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function product(): ProductInterface
    {
        return $this->product;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function applyOffer(): bool
    {
        return $this->quantity >= $this->product->minUnitsToApplyOffer();
    }

    private function ensureMaxProductUnitsNotReached(int $quantity)
    {
        if ($quantity > self::MAX_PRODUCT_UNITS) {
            MaxProductUnitsReached::throw();
        }
    }
}