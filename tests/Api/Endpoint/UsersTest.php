<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Endpoint;

use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Api\Delta\InMemoryStorage;
use Lingoda\BrazeBundle\Api\Object\Event;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use Lingoda\BrazeBundle\Api\Request\TrackUserDataRequest;
use Lingoda\BrazeBundle\Tests\Api\BrazeApiTestCase;
use Lingoda\BrazeBundle\Tests\BrazeAssertionsTrait;

final class UsersTest extends BrazeApiTestCase
{
    use BrazeAssertionsTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockHttpClient();
    }

    public function testTrack(): void
    {
        $this->setMockClientResponses([
            'users/track' => $this->createJsonMockResponse([
                'message' => 'success',
                'events_processed' => 1,
            ]),
        ]);

        $response = $this->users()->track(new TrackUserDataRequest([
            'events' => [
                new Event([
                    'external_id' => new ExternalId('external-id'),
                    'name' => 'event-name',
                    'time' => CarbonImmutable::now(),
                ]),
            ],
        ]));

        self::assertTrue($response->isSuccess());
        self::assertEquals(1, $response->getEventsProcessed());
    }

    public function testEventTracking(): void
    {
        $this->setMockClientResponses([
            'users/track' => $this->createJsonMockResponse([
                'message' => 'success',
                'events_processed' => 2,
            ]),
        ]);

        $apiResponse = $this->users()->trackEvents(
            new Event([
                'external_id' => new ExternalId('external-id'),
                'name' => 'event-name-1',
                'time' => CarbonImmutable::now(),
            ]),
            new Event([
                'external_id' => new ExternalId('external-id'),
                'name' => 'event-name-2',
                'time' => CarbonImmutable::now(),
            ]),
        );

        self::assertTrue($apiResponse->isSuccess());
        self::assertEquals(2, $apiResponse->getEventsProcessed());
    }

    public function testSameAttributeValueIsSentOnce(): void
    {
        $this->setMockClientResponses([
            'users/track' => $this->createJsonMockResponse([
                'message' => 'success',
                'events_processed' => 1,
            ]),
        ]);

        /** @var InMemoryStorage $memoryStore */
        $memoryStore = self::$container->get('test.delta_in_memory_store');

        self::assertEmpty($memoryStore->getData());

        $externalId = new ExternalId('test-user');
        $userAttributes = new UserAttributes([
            'external_id' => $externalId,
            'first_name' => 'John',
        ]);

        // initial call, store empty
        $this->lastResponse = $this->users()->trackAttributes($userAttributes);
        $this->assertEventsProcessed();

        $data = $memoryStore->getData();

        self::assertArrayHasKey('test-user', $data);
        self::assertArrayHasKey('first_name', $data['test-user']);
        self::assertArrayHasKey('external_id', $data['test-user']);
        $lastValue = $data['test-user']['first_name'];

        // update with same value, store data doesn't change
        $this->lastResponse = $this->users()->trackAttributes($userAttributes);
        $this->assertEventsProcessed(0);

        self::assertSame($lastValue, $memoryStore->getData()['test-user']['first_name']);

        $newFirstNameAttribute = new UserAttributes([
            'external_id' => $externalId,
            'first_name' => 'Jim',
        ]);

        // update existing attribute in store
        $this->lastResponse = $this->users()->trackAttributes($newFirstNameAttribute);
        $this->assertEventsProcessed();

        self::assertNotSame($lastValue, $memoryStore->getData()['test-user']['first_name']);
    }
}
