<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Object;

use Lingoda\BrazeBundle\Api\Object\PushToken;
use Lingoda\BrazeBundle\Tests\Api\SerializationTestCase;

final class PushTokenSerializationTest extends SerializationTestCase
{
    /**
     * @dataProvider serializationData
     */
    public function testSerialization(PushToken $pushToken, string $expectedJson): void
    {
        self::assertSame($expectedJson, $this->json($pushToken));
    }

    /**
     * @return iterable<string, array{PushToken, string}>
     */
    public function serializationData(): iterable
    {
        yield 'default' => [
            new PushToken(['app_id' => 'app-id', 'token' => 'token']),
            '{"app_id":"app-id","token":"token"}',
        ];

        yield 'with-device-id' => [
            new PushToken(['app_id' => 'app-id', 'token' => 'token', 'device_id' => 'device-id']),
            '{"app_id":"app-id","token":"token","device_id":"device-id"}',
        ];
    }
}
