<?php
declare(strict_types = 1);

namespace App\Marketplace\Domain\Model\Product;


use App\Marketplace\Domain\Model\Money\Money;

interface ProductInterface
{
    public function id(): string;

    public function money(): Money;

    public function offerMoney(): Money;

    public function minUnitsToApplyOffer(): int;
}