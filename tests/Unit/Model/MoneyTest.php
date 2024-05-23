<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Exception\DomainException;
use App\Model\Money;
use App\Tests\Unit\TestCase;

class MoneyTest extends TestCase
{
    public function testCreationWithSuccess(): void
    {
        $money = new Money(1.23, 'usd');

        self::assertSame(1.23, $money->value);
        self::assertSame('USD', $money->currency);
    }

    public function testCreationWithNegativeValueWillFail(): void
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage('Money amount cannot be a negative number');

        $money = new Money(-1.23, 'usd');
    }

    /**
     * @return iterable<string[]>
     */
    public function creationWithInvalidIsoWillFail(): iterable
    {
        yield ['X', 'Currency must be compatible to ISO 3166-1 alpha-3 standard'];
        yield ['md5', 'Currency must be compatible to ISO 3166-1 alpha-3 standard'];
        yield ['1TC', 'Currency must be compatible to ISO 3166-1 alpha-3 standard'];
    }

    /**
     * @dataProvider creationWithInvalidIsoWillFail
     */
    public function testCreationWithInvalidIsoWillFail(string $iso, string $expectedMessage): void
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage($expectedMessage);

        $money = new Money(1.23, $iso);
    }
}
