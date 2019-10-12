<?php
declare(strict_types=1);

namespace App\Marketplace\Domain\Model\CartLine;

use App\Marketplace\Domain\Model\Product\ProductInterface;

class CartLine
{
    /** @var ProductInterface */
    private $product;
    /** @var int */
    private $quantity;

    public function __construct(ProductInterface $product, int $quantity)
    {
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
}