<?php

declare(strict_types=1);

namespace App\Service\ExchangeSource\CbrRu;

use App\Service\ExchangeRateProvider;

class CbrExchangeRateProvider implements ExchangeRateProvider
{
    public function __construct(
        private readonly CbrHttpClient $client
    ) {
    }

    public function getRate(string $sourceIso, string $targetIso): float
    {
        $ratesMap = $this->fetchRatesMap();
        self::assertCurrencySupported($ratesMap, $sourceIso, 'source');
        self::assertCurrencySupported($ratesMap, $targetIso, 'target');

        $rate = 1;

        if (CbrBaseCurrency::ISO !== $sourceIso) {
            $rate = (float) \bcmul((string) $rate, (string) $ratesMap[$sourceIso], 5);
        }

        if (CbrBaseCurrency::ISO !== $targetIso) {
            $rate = (float) \bcdiv((string) $rate, (string) $ratesMap[$targetIso], 5);
        }

        return $rate;
    }

    /**
     * @return array<string, float>
     */
    private function fetchRatesMap(): array
    {
        $xml = $this->client->loadXml();
        $rates = [CbrBaseCurrency::ISO => 1.0];

        foreach ($xml->Valute as $valute) {
            $rates[strtoupper((string) $valute->CharCode)] = (float) str_replace(',', '.', (string) $valute->VunitRate);
        }

        return $rates;
    }

    /**
     * @param array<string, float> $ratesMap
     */
    private static function assertCurrencySupported(array $ratesMap, string $iso, string $typeName): void
    {
        if (!array_key_exists($iso, $ratesMap)) {
            throw new \RuntimeException(sprintf('Unsupported %s currency provided: "%s"', $typeName, $iso));
        }
    }
}
