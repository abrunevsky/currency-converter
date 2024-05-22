<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\ExchangeSource\CbrRu;

use App\Service\ExchangeSource\CbrRu\HttpClient;
use App\Tests\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

abstract class AbstractCbrTestCase extends TestCase
{
    protected HttpClient&MockObject $clientMock;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(HttpClient::class);
        $this->clientMock->expects(self::once())
            ->method('loadXml')
            ->willReturn(
                simplexml_load_file(__DIR__.'/testData.xml')
            )
        ;
    }
}
