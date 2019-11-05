<?php
declare(strict_types=1);

namespace App\Marketplace\Domain\Cart;

use App\Marketplace\Domain\CartLine\CartLine;
use App\Marketplace\Domain\Currency\Currency;
use App\Marketplace\Domain\Money\Money;
use App\Marketplace\Domain\Product\ProductInterface;
use Countable;

class Cart implements Countable
{
    const DEFAULT_CURRENCY_ISO_CODE = 'EUR';
    const MAX_LINES = 10;

    /** @var CartId */
    private $id;
    /** @var CartLine array */
    private $lines;

    public function __construct(CartId $id)
    {
        $this->id = $id;
        $this->lines = array();
    }

    public static function init()
    {
        $id = CartId::random();

        return new static($id);
    }

    public function id(): CartId
    {
        return $this->id;
    }

    public function addProductWithQuantity(ProductInterface $product, int $quantity)
    {
        $cartLine = new CartLine($product, $quantity);
        $this->addCartLine($cartLine);
    }

    public function totalProducts()
    {
        $acum = 0;
        /** @var CartLine $aLine */
        foreach ($this->lines as $aLine) {
            $acum += $aLine->quantity();
        }
        return $acum;
    }

    public function count()
    {
        return count($this->lines);
    }

    public function removeProduct(ProductInterface $product)
    {
        if (!isset($this->lines[$product->id()])) {
            ProductDoesNotExistInCart::throwBecauseOf($product->id());
        }

        unset($this->lines[$product->id()]);
    }

    public function moneyWithoutOffer(): Money
    {
        $totalMoney = new Money(0, new Currency(self::DEFAULT_CURRENCY_ISO_CODE));

        /** @var CartLine $aLine */
        foreach ($this->lines as $aLine) {
            $product = $aLine->product();
            $productMoney = $product->money();

            $totalMoney = $totalMoney->increaseAmount($productMoney->amount() * $aLine->quantity());
        }

        return $totalMoney;
    }

    public function moneyWithOffer(): Money
    {
        $totalMoney = new Money(0, new Currency(self::DEFAULT_CURRENCY_ISO_CODE));

        /** @var CartLine $aLine */
        foreach ($this->lines as $aLine) {
            $product = $aLine->product();
            $productMoney = $product->money();
            $offerMoney = $product->offerMoney();
            $quantity = $aLine->quantity();

            $amountToIncrease = $aLine->applyOffer() ? $offerMoney->amount() * $quantity : $productMoney->amount() * $quantity;

            $totalMoney = $totalMoney->increaseAmount($amountToIncrease);
        }

        return $totalMoney;
    }

    private function addCartLine(CartLine $cartLine)
    {
        $product = $cartLine->product();
        $productId = $product->id();

        $this->ensureProductHasSameCurrencyThanCart($product->money());

        if (isset($this->lines[$productId])) {

            $currentCartLine = $this->lines[$productId];

            $newCartLine = new CartLine(
                $product,
                $currentCartLine->quantity() + $cartLine->quantity()
            );

            $this->lines[$productId] = $newCartLine;

        } else {
            $this->ensureLinesLimitAreNotReached();
            $this->lines[$product->id()] = $cartLine;
        }
    }

    private function ensureProductHasSameCurrencyThanCart(Money $productMoney)
    {
        if (false === $productMoney->currency()->equals(new Currency(self::DEFAULT_CURRENCY_ISO_CODE))) {
            ProductCurrenciesAreNotTheSame::throw();
        }
    }

    private function ensureLinesLimitAreNotReached()
    {
        if ($this->count() >= self::MAX_LINES) {
            LinesLimitReached::throw();
        }
    }
}