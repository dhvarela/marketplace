<?php
declare(strict_types=1);

namespace App\Tests\Marketplace\Application\Service\Cart;

use App\Marketplace\Application\Service\Cart\CartMoneyConverter;
use App\Marketplace\Infrastructure\Currency\AlphavantageExchange;
use App\Marketplace\Domain\Cart\Cart;
use App\Marketplace\Domain\Currency\Currency;
use App\Marketplace\Domain\Money\Money;
use App\Marketplace\Domain\Product\Product;
use App\Marketplace\Domain\Product\ProductInterface;
use PHPUnit\Framework\TestCase;

class CartMoneyConverterTest extends TestCase
{
    /**
     * @test
     * @vcr cartMoneyConverter.yml
     */
    public function test_should_convert_cart_money_to_another_currency(): void
    {
        $cart = Cart::init();
        $product1 = $this->getProduct('product-a', 10, 9, 'EUR', 3);

        $cart->addProductWithQuantity($product1, 3);

        $alphavantage = new AlphavantageExchange();
        $cartMoneyConverter = new CartMoneyConverter($alphavantage);

        $priceWithoutOfferInBritishPound = $cartMoneyConverter->execute($cart->moneyWithoutOffer(), new Currency('GBP'));
        $priceWithOfferInUsd = $cartMoneyConverter->execute($cart->moneyWithoutOffer(), new Currency('USD'));

        $this->assertEquals(new Currency('GBP'), $priceWithoutOfferInBritishPound->currency());
        $this->assertGreaterThan(0, $priceWithoutOfferInBritishPound->amount());

        $this->assertEquals(new Currency('USD'), $priceWithOfferInUsd->currency());
        $this->assertGreaterThan(0, $priceWithOfferInUsd->amount());
    }

    private function getProduct($id, $amount, $offerAmount, $isoCode, $minUnitsToApplyOffer): ProductInterface
    {
        $money = new Money($amount, new Currency($isoCode));
        $offerMoney = new Money($offerAmount, new Currency($isoCode));

        return new Product($id, $money, $offerMoney, $minUnitsToApplyOffer);
    }
}