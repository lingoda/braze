<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests;

use Lingoda\BrazeBundle\Api\Response\ApiResponseInterface;
use Lingoda\BrazeBundle\Api\Response\ApiUserTrackResponse;

trait BrazeAssertionsTrait
{
    protected ?ApiResponseInterface $lastResponse = null;

    protected function assertResponseSuccessful(): void
    {
        self::assertNotNull($this->lastResponse, 'Unable to access last response');
        self::assertTrue($this->lastResponse->isSuccess());
    }

    protected function assertEventsProcessed(int $eventProcessed = 1): void
    {
        $this->assertResponseSuccessful();
        self::assertInstanceOf(ApiUserTrackResponse::class, $this->lastResponse);
        self::assertSame($eventProcessed, $this->lastResponse->getEventsProcessed());
    }

    protected function assertAttributesProcessed(int $eventProcessed = 1): void
    {
        $this->assertResponseSuccessful();
        self::assertInstanceOf(ApiUserTrackResponse::class, $this->lastResponse);
        self::assertSame($eventProcessed, $this->lastResponse->getAttributesProcessed());
    }

    protected function assertPurchasesProcessed(int $eventProcessed = 1): void
    {
        $this->assertResponseSuccessful();
        self::assertInstanceOf(ApiUserTrackResponse::class, $this->lastResponse);
        self::assertSame($eventProcessed, $this->lastResponse->getPurchasesProcessed());
    }
}
