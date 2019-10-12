<?php

namespace App\Marketplace\Domain\Model\Cart;


use UnderflowException;

class ProductDoesNotExistInCart extends UnderflowException
{
    public static function throwBecauseOf(string $productId)
    {
        throw new self(sprintf("Product %s not found in cart", $productId));
    }
}