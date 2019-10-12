<?php
declare(strict_types=1);

namespace App\Marketplace\Domain\Model\Product;

use App\Marketplace\Domain\Model\Money\Money;

class Product implements ProductInterface
{
    /** @var string */
    private $id;
    /** @var Money */
    private $money;
    /** @var Money */
    private $offerMoney;
    /** @var int */
    private $minUnitsToApplyOffer;

    public function __construct(string $id, Money $money, Money $offerMoney, int $minUnitsToApplyOffer)
    {
        $this->id = $id;
        $this->money = $money;
        $this->offerMoney = $offerMoney;
        $this->minUnitsToApplyOffer = $minUnitsToApplyOffer;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return Money
     */
    public function money(): Money
    {
        return $this->money;
    }

    /**
     * @return Money
     */
    public function offerMoney(): Money
    {
        return $this->offerMoney;
    }

    /**
     * @return int
     */
    public function minUnitsToApplyOffer(): int
    {
        return $this->minUnitsToApplyOffer;
    }
}