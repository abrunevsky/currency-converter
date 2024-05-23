<?php

declare(strict_types=1);

namespace App\Service\ExchangeSource\CbrRu;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;

interface CbrHttpClientInterface
{
    public function loadXml(): \SimpleXMLElement;
}
