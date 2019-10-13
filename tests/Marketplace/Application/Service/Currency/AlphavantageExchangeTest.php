<?php
declare(strict_types=1);

namespace App\Tests\Marketplace\Application\Service\Currency;

use App\Marketplace\Application\Service\Currency\AlphavantageExchange;
use App\Marketplace\Domain\Model\Currency\Currency;
use App\Marketplace\Domain\Model\Money\Money;
use PHPUnit\Framework\TestCase;

class AlphavantageExchangeTest extends TestCase
{
    protected $alphavantage;

    protected function setUp(): void
    {
        $this->alphavantage = new AlphavantageExchange();
    }

    /** @test */
    public function test_should_instantiate_an_alphavantage_exchange(): void
    {
        $this->assertInstanceOf(AlphavantageExchange::class, $this->alphavantage);
    }

    /**
     * @test
     * @vcr alphavantage.yml
     */
    public function test_should_convert_money_to_another_currency(): void
    {
        $money = new Money(1000, new Currency('EUR'));
        $conversionCurrency = new Currency('USD');

        $convertedMoney = $this->alphavantage->execute($money, $conversionCurrency);

        $currencyExpected = new Currency('USD');

        $this->assertEquals($currencyExpected, $convertedMoney->currency());
        $this->assertGreaterThan(0, $convertedMoney->amount());
    }
}