<?php
declare(strict_types=1);

namespace App\Marketplace\Application\Service\Currency;

use App\Marketplace\Domain\Currency\Currency;
use App\Marketplace\Domain\Money\Money;

interface CurrencyExchangeRate
{
    public function execute(Money $from, Currency $to): Money;
}