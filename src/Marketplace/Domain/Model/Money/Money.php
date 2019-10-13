<?php
declare(strict_types=1);

namespace App\Marketplace\Domain\Model\Money;

use App\Marketplace\Domain\Model\Currency\Currency;

class Money
{
    /** @var int $amount */
    private $amount;
    /** @var Currency $currency */
    private $currency;

    public function __construct($amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function equals(Money $money): bool
    {
        return
            $money->currency()->equals($this->currency()) &&
            $money->amount() === $this->amount();
    }

    public function increaseAmount($anAmount)
    {
        return new self(
            $this->amount() + $anAmount,
            $this->currency()
        );
    }
}