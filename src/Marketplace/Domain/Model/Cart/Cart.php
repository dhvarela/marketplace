<?php
declare(strict_types=1);

namespace App\Marketplace\Domain\Model\Cart;

use App\Marketplace\Domain\Model\CartLine\CartLine;
use App\Marketplace\Domain\Model\Product\ProductInterface;
use Countable;

class Cart implements Countable
{
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
    
    public function to1talProducts()
    {
        return 0;
    }
    
    public function count()
    {
        return count($this->lines);
    }

    private function addCartLine(CartLine $cartLine)
    {
        $product = $cartLine->product();
        $this->lines[$product->id()] = $cartLine;
    }
}