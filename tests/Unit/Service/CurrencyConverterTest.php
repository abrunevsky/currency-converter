<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Model\Money;
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
     * @return iterable<array{Money, string, float, float}>
     */
    public function convertCurrenciesProvider(): iterable
    {
        yield [new Money(100.0, 'USD'), 'BYN', 2.2001, 220.01];

        yield [new Money(112.0, 'BYN'), 'USD', 0.5, 56.0];

        yield [new Money(100, 'CUP'), 'USD', 1, 100.0];
    }

    /**
     * @dataProvider convertCurrenciesProvider
     */
    public function testConvertCurrenciesWithSuccess(Money $sourceAmount, string $targetIso, float $rateMock, float $expectedTargetValue): void
    {
        $this->exchangeProviderMock->expects(self::once())
            ->method('getRate')
            ->with($sourceAmount->iso, $targetIso)
            ->willReturn($rateMock)
        ;

        $targetAmount = $this->converter->convert($sourceAmount, $targetIso);

        self::assertSame($expectedTargetValue, $targetAmount->value);
    }
}
