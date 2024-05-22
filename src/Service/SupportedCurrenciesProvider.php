<?php

declare(strict_types=1);

namespace App\Service;

interface SupportedCurrenciesProvider
{
    /**
     * @return array<string, string>
     */
    public function getList(): array;
}
