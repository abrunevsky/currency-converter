<?php

declare(strict_types=1);

namespace App\Service;

class CurrencyConverter
{
    public function __construct(
        private readonly ExchangeRateProvider $exchangeRateProvider,
    ) {
    }

    public function convert(float $sourceAmount, string $sourceCurrencyIso, string $targetCurrencyIso): float
    {
        return (float) \bcmul((string) $sourceAmount, (string) $this->exchangeRateProvider->getRate($sourceCurrencyIso, $targetCurrencyIso), 5);
    }
}
