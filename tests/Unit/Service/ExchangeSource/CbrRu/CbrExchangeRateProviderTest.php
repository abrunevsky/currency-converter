<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\ExchangeSource\CbrRu;

use App\Service\ExchangeSource\CbrRu\CbrExchangeRateProvider;

class CbrExchangeRateProviderTest extends AbstractCbrTestCase
{
    private CbrExchangeRateProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = new CbrExchangeRateProvider($this->clientMock);
    }

    /**
     * @return iterable<array{string, string, float}>
     */
    public function getRateWithSuccessProvider(): iterable
    {
        yield ['USD', 'BYN', 3.2008];
        yield ['BYN', 'RUB', 28.1769];
        yield ['RUB', 'HUF', 3.9465];
    }

    /**
     * @dataProvider getRateWithSuccessProvider
     */
    public function testGetRateWithSuccess(string $sourceIso, string $targetIso, float $expectedRate): void
    {
        $rate = $this->provider->getRate($sourceIso, $targetIso);

        self::assertSame($expectedRate, $rate);
    }

    /**
     * @return iterable<array{string, string, string}>
     */
    public function getRateWithUnsupportedCurrencies(): iterable
    {
        yield ['USD', 'BYR', 'Unsupported target currency provided: "BYR"'];
        yield ['BYN', 'XXX', 'Unsupported target currency provided: "XXX"'];
        yield ['YYY', 'HUF', 'Unsupported source currency provided: "YYY"'];
    }

    /**
     * @dataProvider getRateWithUnsupportedCurrencies
     */
    public function testGetRateWithUnsupportedCurrenciesWillThrowException(
        string $sourceIso,
        string $targetIso,
        string $expectedExceprionMessage
    ): void {
        $this->expectExceptionMessage($expectedExceprionMessage);

        $this->provider->getRate($sourceIso, $targetIso);
    }
}
