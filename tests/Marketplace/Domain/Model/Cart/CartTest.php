<?php
declare(strict_types=1);

use App\Marketplace\Domain\Model\Cart\Cart;
use App\Marketplace\Domain\Model\Money\Money;
use App\Marketplace\Domain\Model\Currency\Currency;
use App\Marketplace\Domain\Model\Product\Product;
use App\Marketplace\Domain\Model\Product\ProductInterface;
use App\Marketplace\Domain\Model\Cart\LinesLimitReached;
use App\Marketplace\Domain\Model\Cart\ProductDoesNotExistInCart;
use App\Marketplace\Domain\Model\Cart\ProductCurrenciesAreNotTheSame;
use App\Marketplace\Domain\Model\CartLine\CartLine;
use App\Marketplace\Domain\Model\CartLine\MaxProductUnitsReached;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    /** @test */
    public function test_should_instantiate_an_empty_cart_with_an_id(): void
    {
        $cart = Cart::init();
        $this->assertNotEmpty($cart->id());
        $this->assertEquals(0, $cart->totalProducts());
    }

    /** @test */
    public function test_should_add_product(): void
    {
        $cart = Cart::init();
        $product = $this->getProduct('product-a', 10, 9, 'EUR', 3);

        $cart->addProductWithQuantity($product, 1);

        $this->assertCount(1, $cart);
    }

    /** @test */
    public function test_should_add_product_with_quantity(): void
    {
        $cart = Cart::init();
        $product = $this->getProduct('product-a', 10, 9, 'EUR', 3);

        $cart->addProductWithQuantity($product, 5);

        $this->assertCount(1, $cart);
        $this->assertEquals(5, $cart->totalProducts());
    }

    /** @test */
    public function test_should_fail_adding_a_product_with_different_cart_currency(): void
    {
        $cart = Cart::init();
        $product = $this->getProduct('product-a', 10, 9, 'USD', 3);

        $this->expectException(ProductCurrenciesAreNotTheSame::class);
        $cart->addProductWithQuantity($product, 1);
    }

    /** @test */
    public function test_should_add_same_product_in_different_actions(): void
    {
        $cart = Cart::init();
        $product1 = $this->getProduct('product-a', 10, 9, 'EUR', 3);
        $product2 = $this->getProduct('product-b', 8, 7, 'EUR', 4);

        $cart->addProductWithQuantity($product1, 3);
        $cart->addProductWithQuantity($product2, 6);
        $cart->addProductWithQuantity($product1, 2);

        $this->assertCount(2, $cart);
        $this->assertEquals(11, $cart->totalProducts());
    }

    /** @test */
    public function test_should_remove_an_existing_product() : void
    {
        $cart = Cart::init();
        $product1 = $this->getProduct('product-a', 10, 9, 'EUR', 3);
        $product2 = $this->getProduct('product-b', 8, 7, 'EUR', 4);

        $cart->addProductWithQuantity($product1, 3);
        $cart->addProductWithQuantity($product2, 6);

        $cart->removeProduct($product1);

        $this->assertCount(1, $cart);
    }

    /** @test */
    public function test_should_fail_trying_to_remove_a_non_existing_product() : void
    {
        $cart = Cart::init();
        $product1 = $this->getProduct('product-a', 10, 9, 'EUR', 3);

        $this->expectException(ProductDoesNotExistInCart::class);
        $cart->removeProduct($product1);
    }

    /** @test */
    public function test_should_fail_when_max_cart_lines_are_reached() : void
    {
        $cart = Cart::init();
        $products = $this->getRandomProducts();

        $this->expectException(LinesLimitReached::class);

        foreach ($products as $aProduct) {
            $cart->addProductWithQuantity($aProduct, rand(1,10));
        }
    }

    /** @test */
    public function test_should_fail_when_max_product_units_are_reached() : void
    {
        $cart = Cart::init();
        $product1 = $this->getProduct('product-a', 10, 9, 'EUR', 3);

        $this->expectException(MaxProductUnitsReached::class);

        $cart->addProductWithQuantity($product1, CartLine::MAX_PRODUCT_UNITS + 1);
    }

    /** @test */
    public function test_should_calculate_amount_without_offer() : void
    {
        $cart = Cart::init();
        $product1 = $this->getProduct('product-a', 10, 9, 'EUR', 3);

        $cart->addProductWithQuantity($product1, 2);

        $moneyExpected = new Money(20, new Currency('EUR'));

        $this->assertEquals($moneyExpected, $cart->moneyWithoutOffer());
    }

    /** @test */
    public function test_should_calculate_amount_with_offers() : void
    {
        $cart = Cart::init();
        $product1 = $this->getProduct('product-a', 10, 9, 'EUR', 3);

        $cart->addProductWithQuantity($product1, 4);

        $moneyExpected = new Money(36, new Currency('EUR'));

        $this->assertEquals($moneyExpected, $cart->moneyWithOffer());
    }

    /** @test */
    public function test_should_calculate_amount_with_offer_when_cart_has_offer_and_non_offer_products() : void
    {
        $cart = Cart::init();
        $product1 = $this->getProduct('product-a', 10, 9, 'EUR', 3);
        $product2 = $this->getProduct('product-b', 15, 12, 'EUR', 4);

        $cart->addProductWithQuantity($product1, 2);
        $cart->addProductWithQuantity($product2, 4);

        $moneyExpected = new Money(68, new Currency('EUR'));

        $this->assertEquals($moneyExpected, $cart->moneyWithOffer());
    }

    private function getProduct($id, $amount, $offerAmount, $isoCode, $minUnitsToApplyOffer): ProductInterface
    {
        $money = new Money($amount, new Currency($isoCode));
        $offerMoney = new Money($offerAmount, new Currency($isoCode));

        return new Product($id, $money, $offerMoney, $minUnitsToApplyOffer);
    }

    private function getRandomProducts() : array
    {
        $products = array();
        for ($i=1; $i<= (Cart::MAX_LINES + 1); $i++) {
            $amount = rand(5,16);
            $offerAmount = $amount - rand(1,3);
            $products[] = $this->getProduct('product-' . $i, $amount, $offerAmount, 'EUR', rand(2,5));
        }

        return $products;
    }
}