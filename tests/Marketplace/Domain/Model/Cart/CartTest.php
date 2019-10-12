<?php
declare(strict_types=1);

use App\Marketplace\Domain\Model\Cart\Cart;
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
}