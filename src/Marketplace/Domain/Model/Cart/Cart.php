<?php
declare(strict_types=1);

namespace App\Marketplace\Domain\Model\Cart;

use App\Marketplace\Domain\Model\CartLine\CartLine;
use App\Marketplace\Domain\Model\Product\ProductInterface;
use Countable;

class Cart implements Countable
{
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

    private function addCartLine(CartLine $cartLine)
    {
        $product = $cartLine->product();
        $productId = $product->id();

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

    private function ensureLinesLimitAreNotReached()
    {
        if ($this->count() >= self::MAX_LINES) {
            LinesLimitReached::throw();
        }
    }
}