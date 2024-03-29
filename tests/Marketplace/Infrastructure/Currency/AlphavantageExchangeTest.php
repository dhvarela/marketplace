<?php
declare(strict_types=1);

namespace App\Tests\Marketplace\Application\Service\Currency;

use App\Marketplace\Infrastructure\Currency\AlphavantageExchange;
use App\Marketplace\Domain\Currency\Currency;
use App\Marketplace\Domain\Money\Money;
use PHPUnit\Framework\TestCase;
use RuntimeException;

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

    /**
     * @test
     * @vcr alphavantageWithBadCurrency.yml
     */
    public function test_should_fail_when_iso_code_currency_is_not_correct(): void
    {
        $money = new Money(1000, new Currency('EUR'));
        $conversionCurrency = new Currency('YZS');

        $this->expectException(RuntimeException::class);

        $this->alphavantage->execute($money, $conversionCurrency);
    }
}