<?php
declare(strict_types=1);

namespace App\Marketplace\Application\Service\Currency;

use App\Marketplace\Domain\Model\Currency\Currency;
use App\Marketplace\Domain\Model\Money\Money;

interface CurrencyExchangeRate
{
    public function execute(Money $from, Currency $to): Money;
}