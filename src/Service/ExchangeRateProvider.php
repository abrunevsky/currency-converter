<?php

declare(strict_types=1);

namespace App\Service;

interface ExchangeRateProvider
{
    public function getRate(string $sourceIso, string $targetIso): float;
}
