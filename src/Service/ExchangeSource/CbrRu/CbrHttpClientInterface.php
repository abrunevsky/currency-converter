<?php

declare(strict_types=1);

namespace App\Service\ExchangeSource\CbrRu;

interface CbrHttpClientInterface
{
    public function loadXml(): \SimpleXMLElement;
}
