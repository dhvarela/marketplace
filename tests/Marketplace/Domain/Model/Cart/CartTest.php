<?php
declare(strict_types=1);

use App\Marketplace\Domain\Model\Cart\Cart;
use App\Marketplace\Domain\Model\Product\Product;
use App\Marketplace\Domain\Model\Product\ProductInterface;
use App\Marketplace\Domain\Model\Money\Money;
use App\Marketplace\Domain\Model\Currency\Currency;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    /** @test */
    public function test_should_instantiate_an_empty_cart_with_an_id(): void
    {
        $cart = Cart::init();
        $this->assertNotEmpty($cart->id());
        $this->assertEquals(0, $cart->to1talProducts());
    }

    /** @test */
    public function test_should_add_product(): void
    {
        $cart = Cart::init();
        $product = $this->getProduct('product-a', 10, 9, 'EUR', 3);

        $cart->addProductWithQuantity($product, 1);

        $this->assertCount(1, $cart);
    }

    private function getProduct($id, $amount, $offerAmount, $isoCode, $minUnitsToApplyOffer): ProductInterface
    {
        $money = new Money($amount, new Currency($isoCode));
        $offerMoney = new Money($offerAmount, new Currency($isoCode));

        return new Product($id, $money, $offerMoney, $minUnitsToApplyOffer);
    }
}