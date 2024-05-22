<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\CurrencyConverter;
use App\Service\ExchangeRateProvider;
use App\Tests\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CurrencyConverterTest extends TestCase
{
    private CurrencyConverter $converter;
    private ExchangeRateProvider&MockObject $exchangeProviderMock;

    protected function setUp(): void
    {
        $this->exchangeProviderMock = $this->createMock(ExchangeRateProvider::class);
        $this->converter = new CurrencyConverter($this->exchangeProviderMock);
    }

    /**
     * @return iterable<mixed>
     */
    public function convertCurrenciesProvider(): iterable
    {
        yield ['USD', 100.0, 'BYN', 2.2001, 220.01];

        yield ['BYN', 112.0, 'USD', 0.5, 56.0];
    }

    /**
     * @dataProvider convertCurrenciesProvider
     */
    public function testConvertCurrenciesWithSuccess(string $sourceIso, float $sourceAmount, string $targetIso, float $rateMock, float $expectedTargetAmount): void
    {
        $this->exchangeProviderMock->expects(self::once())
            ->method('getRate')
            ->with($sourceIso, $targetIso)
            ->willReturn($rateMock)
        ;

        $targetAmount = $this->converter->convert($sourceAmount, $sourceIso, $targetIso);

        self::assertSame($expectedTargetAmount, $targetAmount);
    }
}
