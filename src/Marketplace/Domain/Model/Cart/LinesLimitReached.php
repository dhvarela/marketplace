<?php

namespace App\Marketplace\Domain\Model\Cart;


use OverflowException;

class LinesLimitReached extends OverflowException
{
    public static function throw()
    {
        throw new self("The cart is full of products");
    }
}