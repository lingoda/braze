<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Object;

use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Api\Object\AppId;
use Lingoda\BrazeBundle\Api\Object\BrazeId;
use Lingoda\BrazeBundle\Api\Object\Event;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Properties;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Tests\Api\SerializationTestCase;

final class EventSerializationTest extends SerializationTestCase
{
    private const APP_ID = 'F995D9AA-2BE4-4F28-8B21-5BA0D03D85A8';

    /**
     * @dataProvider eventSerializationData
     */
    public function testEventSerialization(Event $event, string $expected): void
    {
        self::assertSame($expected, $this->json($event));
    }

    /**
     * @return iterable<string, array{Event, string}>
     */
    public function eventSerializationData(): iterable
    {
        $appId = new AppId(self::APP_ID);
        $brazeId = new BrazeId('braze-id');
        $userAlias = new UserAlias('alias-name', 'alias-label');
        $externalId = new ExternalId('external-id');

        yield 'event-with-external-id' => [
            new Event([
                'external_id' => $externalId,
                'app_id' => $appId,
                'time' => new CarbonImmutable('2021-08-10 10:00:00'),
                'name' => 'event-name',
            ]),
            (string) json_encode([
                'time' => '2021-08-10T10:00:00+00:00',
                'external_id' => $externalId->getValue(),
                'app_id' => self::APP_ID,
                'name' => 'event-name',
            ]),
        ];

        yield 'event-with-properties' => [
            new Event([
                'external_id' => $externalId,
                'app_id' => $appId,
                'name' => 'event-name',
                'time' => new CarbonImmutable('2021-08-10 10:00:00'),
                'properties' => new Properties([
                    'string' => 'value',
                    'boolean' => true,
                    'integer' => 12,
                    'float' => 0.1,
                    'datetime' => new CarbonImmutable('2021-08-10 10:00:00'),
                ]),
            ]),
            (string) json_encode([
                'time' => '2021-08-10T10:00:00+00:00',
                'external_id' => $externalId->getValue(),
                'app_id' => self::APP_ID,
                'name' => 'event-name',
                'properties' => [
                    'string' => 'value',
                    'boolean' => true,
                    'integer' => 12,
                    'float' => 0.1,
                    'datetime' => '2021-08-10T10:00:00+00:00',
                ],
            ]),
        ];

        yield 'event-with-braze-id' => [
            new Event([
                'braze_id' => $brazeId,
                'app_id' => $appId,
                'time' => new CarbonImmutable('2021-08-10 10:00:00'),
                'name' => 'event-name',
            ]),
            (string) json_encode([
                'time' => '2021-08-10T10:00:00+00:00',
                'braze_id' => $brazeId->getValue(),
                'app_id' => self::APP_ID,
                'name' => 'event-name',
            ]),
        ];

        yield 'event-with-user-alias' => [
            new Event([
                'user_alias' => $userAlias,
                'app_id' => $appId,
                'time' => new CarbonImmutable('2021-08-10 10:00:00'),
                'name' => 'event-name',
            ]),
            (string) json_encode([
                'time' => '2021-08-10T10:00:00+00:00',
                'user_alias' => [
                    'alias_name' => 'alias-name',
                    'alias_label' => 'alias-label',
                ],
                'app_id' => self::APP_ID,
                'name' => 'event-name',
            ]),
        ];

        yield 'event-with-empty-properties' => [
            new Event([
                'user_alias' => $userAlias,
                'app_id' => $appId,
                'time' => new CarbonImmutable('2021-08-10 10:00:00'),
                'name' => 'event-name',
                'properties' => new Properties(),
            ]),
            (string) json_encode([
                'time' => '2021-08-10T10:00:00+00:00',
                'user_alias' => [
                    'alias_name' => 'alias-name',
                    'alias_label' => 'alias-label',
                ],
                'app_id' => self::APP_ID,
                'name' => 'event-name',
                'properties' => [],
            ]),
        ];
    }
}
