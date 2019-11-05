<?php

namespace App\Marketplace\Application\Service\Cart;

use App\Marketplace\Application\Service\Currency\CurrencyExchangeRate;
use App\Marketplace\Domain\Currency\Currency;
use App\Marketplace\Domain\Money\Money;

class CartMoneyConverter
{
    private $exchangeRate;

    public function __construct(CurrencyExchangeRate $exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
    }

    public function __invoke(Money $money, Currency $currency): Money
    {
        return $this->exchangeRate->execute($money, $currency);
    }
}