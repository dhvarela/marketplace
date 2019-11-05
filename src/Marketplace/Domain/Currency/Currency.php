<?php
declare(strict_types=1);

namespace App\Marketplace\Domain\Currency;

use InvalidArgumentException;

class Currency
{
    /** @var string $isoCode */
    private $isoCode;

    public function __construct($isoCode)
    {
        $this->setIsoCode($isoCode);
    }

    private function setIsoCode($isoCode)
    {
        if (!preg_match('/^[A-Z]{3}$/', $isoCode)) {
            throw new InvalidArgumentException();
        }
        $this->isoCode = $isoCode;
    }

    public function isoCode(): string
    {
        return $this->isoCode;
    }

    public function equals(Currency $currency): bool
    {
        return $currency->isoCode() === $this->isoCode();
    }
}