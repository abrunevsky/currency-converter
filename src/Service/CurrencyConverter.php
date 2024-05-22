<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Money;

class CurrencyConverter
{
    public function __construct(
        private readonly ExchangeRateProvider $exchangeRateProvider,
    ) {
    }

    public function convert(Money $sourceAmount, string $targetCurrencyIso): Money
    {
        $targetAmount = new Money(0, $targetCurrencyIso);
        $rate = $this->exchangeRateProvider->getRate($sourceAmount->iso, $targetAmount->iso);
        $targetValue = (float) \bcmul((string) $sourceAmount->value, (string) $rate, 5);

        return $targetAmount->withValue($targetValue);
    }
}
