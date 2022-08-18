<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Integration;

use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Api\Object\Event;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Properties;
use Lingoda\BrazeBundle\Api\Response\ApiUserTrackResponse;
use Lingoda\BrazeBundle\Tests\Api\BrazeApiTestCase;

/**
 * @group braze-integration
 */
final class UserEventTrackingTest extends BrazeApiTestCase
{
    public function testSimpleEventTracking(): void
    {
        $event = $this->createEvent('custom-event1');

        $this->trackEvents($event);
        $this->assertEventsProcessed();
    }

    public function testMultipleEventsTracking(): void
    {
        $event1 = $this->createEvent('custom-event2');
        $event2 = $this->createEvent('custom-event3');

        $this->trackEvents($event1, $event2);
        $this->assertEventsProcessed(2);
    }

    public function testTrackingWithEventProperties(): void
    {
        $event = $this->createEvent('custom-event-with-properties', [
            'test_prop' => 'success',
        ]);

        $this->trackEvents($event);
        $this->assertResponseSuccessful();
    }

    private function trackEvents(Event ...$event): ApiUserTrackResponse
    {
        $this->lastResponse = $this->users()->trackEvents(...$event);

        return $this->lastResponse;
    }

    /**
     * @param array<string, mixed>  $properties
     */
    private function createEvent(string $eventName, array $properties = []): Event
    {
        $data = [
            'external_id' => new ExternalId('test-integration-user-event-tracking'),
            'app_id' => $this->appId,
            'name' => $eventName,
            'time' => CarbonImmutable::now(),
        ];

        if (!empty($properties)) {
            $data['properties'] = new Properties($properties);
        }

        return new Event($data);
    }
}
