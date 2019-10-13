<?php

namespace App\Marketplace\Domain\Model\Cart;


use DomainException;

class ProductCurrenciesAreNotTheSame extends DomainException
{
    public static function throw()
    {
        throw new self("The product currencies are not the same");
    }
}