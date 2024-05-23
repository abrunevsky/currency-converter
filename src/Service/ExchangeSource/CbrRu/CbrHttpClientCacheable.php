<?php

declare(strict_types=1);

namespace App\Service\ExchangeSource\CbrRu;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CbrHttpClientCacheable implements CbrHttpClientInterface
{
    private const CAHCE_KEY = 'cbr-rates';

    public function __construct(
        private readonly CbrHttpClientInterface $client,
        private readonly CacheInterface $cache,
        private readonly int $ttl,
    ) {
    }

    public function loadXml(): \SimpleXMLElement
    {
        $serialisedXml = $this->cache->get(self::CAHCE_KEY, function (ItemInterface $item) {
            $item->expiresAfter($this->ttl);

            return (string) $this->client->loadXml()->asXML();
        });

        return new \SimpleXMLElement($serialisedXml);
    }
}
