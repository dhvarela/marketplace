<?php

namespace App\Marketplace\Domain\CartLine;


use OverflowException;

class MaxProductUnitsReached extends OverflowException
{
    public static function throw()
    {
        throw new self("The maximum product units is " . CartLine::MAX_PRODUCT_UNITS);
    }
}