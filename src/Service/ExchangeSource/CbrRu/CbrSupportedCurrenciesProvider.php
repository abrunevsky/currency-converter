<?php

declare(strict_types=1);

namespace App\Service\ExchangeSource\CbrRu;

use App\Service\SupportedCurrenciesProvider;

class CbrSupportedCurrenciesProvider implements SupportedCurrenciesProvider
{
    public function __construct(
        private readonly HttpClient $client
    ) {
    }

    public function getList(): array
    {
        $xml = $this->client->loadXml();

        $list = [BaseCurrency::ISO => BaseCurrency::NAME];
        foreach ($xml->Valute as $valute) {
            $list[strtoupper((string) $valute->CharCode)] = (string) $valute->Name;
        }

        ksort($list);

        return $list;
    }
}
