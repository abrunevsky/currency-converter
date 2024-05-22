<?php

declare(strict_types=1);

namespace App\Service\ExchangeSource\CbrRu;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;

class CbrHttpClient
{
    private const ENDPOINT = 'https://www.cbr.ru/scripts/XML_daily_eng.asp';

    public function __construct(
        private readonly ClientInterface $client
    ) {
    }

    public function loadXml(): \SimpleXMLElement
    {
        $response = $this->client->sendRequest(new Request('GET', self::ENDPOINT, ['Accept' => 'text/xml']));

        if (200 != $response->getStatusCode()) {
            $exceptionMessage = sprintf(
                'Exchange service "%s" returnes unexpected response code %d!',
                self::ENDPOINT,
                $response->getStatusCode()
            );
            throw new \RuntimeException($exceptionMessage);
        }

        return new \SimpleXMLElement((string) $response->getBody());
    }
}
