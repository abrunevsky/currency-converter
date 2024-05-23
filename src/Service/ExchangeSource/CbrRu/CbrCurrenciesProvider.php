<?php

declare(strict_types=1);

namespace App\Service\ExchangeSource\CbrRu;

use App\Service\SupportedCurrenciesProvider;

class CbrCurrenciesProvider implements SupportedCurrenciesProvider
{
    public function __construct(
        private readonly CbrHttpClientInterface $client
    ) {
    }

    public function getList(): array
    {
        $xml = $this->client->loadXml();

        $list = [CbrBaseCurrency::ISO => CbrBaseCurrency::NAME];
        foreach ($xml->Valute as $valute) {
            $list[strtoupper((string) $valute->CharCode)] = (string) $valute->Name;
        }

        ksort($list);

        return $list;
    }
}
