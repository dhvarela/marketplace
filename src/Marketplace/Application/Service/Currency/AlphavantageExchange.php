<?php

namespace App\Marketplace\Application\Service\Currency;


use App\Marketplace\Domain\Model\Currency\Currency;
use App\Marketplace\Domain\Model\Money\Money;
use Dotenv\Dotenv;
use Exception;
use GuzzleHttp\Client;
use RuntimeException;

class AlphavantageExchange implements CurrencyExchangeRate
{
    const DS = DIRECTORY_SEPARATOR;
    const CURRENCY_EXCHANGE_RATE_FUNCTION = 'CURRENCY_EXCHANGE_RATE';
    const REALTIME_RATE_OBJECT = 'Realtime Currency Exchange Rate';
    const EXCHANGE_RATE_ATTRIBUTE = '5. Exchange Rate';

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;

        $dotenv = Dotenv::create(
            __DIR__ . self::DS . '..' . self::DS . '..' . self::DS . '..' . self::DS . '..' . self::DS . '..'
        );
        $dotenv->load();
    }

    public function execute(Money $from, Currency $to): Money
    {
        $url = $this->createUrl($from->currency(), $to);

        try {

            $res = file_get_contents($url);

            $realtimeCurrencyExchangeRate = json_decode($res);
            $exchangeRate = $realtimeCurrencyExchangeRate->{self::REALTIME_RATE_OBJECT}->{self::EXCHANGE_RATE_ATTRIBUTE};

        } catch (Exception $e) {
            throw new RuntimeException('Alphavantage exchange conversion error');
        }

        return new Money(
            intval(round($exchangeRate * $from->amount()/100, 2) * 100),
            $to
        );
    }

    private function createUrl(Currency $from, Currency $to)
    {
        $config = self::config();

        $params = array(
            'function' => self::CURRENCY_EXCHANGE_RATE_FUNCTION,
            'from_currency' => $from->isoCode(),
            'to_currency' => $to->isoCode(),
            'apikey' => $config['api_key']
        );

        return $config['base_url'] . '?' . http_build_query($params);
    }

    private static function config()
    {
        return [
            'api_key'  => getenv('ALPHAVANTAGE_KEY'),
            'base_url' => getenv('ALPHAVANTAGE_BASE_URL'),
        ];
    }
}