<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Integration;

use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Properties;
use Lingoda\BrazeBundle\Api\Object\Purchase;
use Lingoda\BrazeBundle\Tests\Api\BrazeApiTestCase;

/**
 * @group braze-integration
 */
final class UserPurchaseTrackingTest extends BrazeApiTestCase
{
    private ExternalId $externalId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->externalId = new ExternalId('test-integration-user-purchase-tracking');
    }

    public function testPurchaseTracking(): void
    {
        $purchase = new Purchase([
            'external_id' => $this->externalId,
            'app_id' => $this->appId,
            'price' => 10.00,
            'product_id' => 'Test Product Purchase',
            'time' => CarbonImmutable::now(),
            'currency' => 'USD',
        ]);

        $this->assertPurchaseProcessedSuccessful($purchase);
    }

    public function testCanTrackPurchaseWithProperties(): void
    {
        $purchase = new Purchase([
            'external_id' => $this->externalId,
            'app_id' => $this->appId,
            'price' => 10.00,
            'product_id' => 'Test Product Purchase with Properties',
            'time' => CarbonImmutable::now(),
            'currency' => 'USD',
            'properties' => new Properties([
                'custom_prop' => 'example_value',
            ]),
        ]);

        $this->assertPurchaseProcessedSuccessful($purchase);
    }

    private function assertPurchaseProcessedSuccessful(Purchase $purchase): void
    {
        $this->lastResponse = $this->users()->trackPurchases($purchase);
        $this->assertPurchasesProcessed();
    }
}
