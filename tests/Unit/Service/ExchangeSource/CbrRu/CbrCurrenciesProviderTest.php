<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\ExchangeSource\CbrRu;

use App\Service\ExchangeSource\CbrRu\CbrCurrenciesProvider;

class CbrCurrenciesProviderTest extends AbstractCbrTestCase
{
    private CbrCurrenciesProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = new CbrCurrenciesProvider($this->clientMock);
    }

    public function testGetListWillReturnArray(): void
    {
        self::assertSame(
            [
                'AUD' => 'Australian Dollar',
                'BYN' => 'Belarussian Ruble',
                'CAD' => 'Canadian Dollar',
                'CNY' => 'China Yuan',
                'EUR' => 'Euro',
                'GBP' => 'British Pound Sterling',
                'GEL' => 'Georgia Lari',
                'HUF' => 'Hungarian Forint',
                'JPY' => 'Japanese Yen',
                'RUB' => 'Russian ruble',
                'USD' => 'US Dollar',
            ],
            $this->provider->getList()
        );
    }
}
