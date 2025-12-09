<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Object\Export\UserData\PushToken;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\PushToken
 */
final class PushTokenTest extends TestCase
{
    public function testFromArrayAndToArrayRoundTrip(): void
    {
        // Setup
        $data = [
            'app' => 'MovieCanon',
            'platform' => 'Android',
            'token' => '12345abcd',
            'device_id' => '312ef2c1-83db-4789-967-554545a1bf7a',
            'notifications_enabled' => true,
        ];

        // Execution
        $pushToken = PushToken::fromArray($data);
        $result = $pushToken->toArray();

        // Assertion
        self::assertSame('MovieCanon', $pushToken->app);
        self::assertSame('Android', $pushToken->platform);
        self::assertSame('12345abcd', $pushToken->token);
        self::assertSame('312ef2c1-83db-4789-967-554545a1bf7a', $pushToken->deviceId);
        self::assertTrue($pushToken->notificationsEnabled);
        self::assertSame($data, $result);
    }
}
