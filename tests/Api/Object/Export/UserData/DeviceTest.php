<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Object\Export\UserData\Device;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\Device
 */
final class DeviceTest extends TestCase
{
    /**
     * @return iterable<string, array{
     *     input: array<string, mixed>,
     *     expectedOutput: array<string, mixed>
     * }>
     */
    public static function provideTestFromArrayAndToArrayData(): iterable
    {
        yield 'required fields only' => [
            'input' => [
                'model' => 'Pixel XL',
                'os' => 'Android (Q)',
                'device_id' => '312ef2c1-83db-4789-967-554545a1bf7a',
                'ad_tracking_enabled' => true,
            ],
            'expectedOutput' => [
                'model' => 'Pixel XL',
                'os' => 'Android (Q)',
                'device_id' => '312ef2c1-83db-4789-967-554545a1bf7a',
                'ad_tracking_enabled' => true,
            ],
        ];

        yield 'all fields set' => [
            'input' => [
                'model' => 'iPhone 14',
                'os' => 'iOS 16.0',
                'device_id' => 'abc123',
                'ad_tracking_enabled' => false,
                'carrier' => 'Verizon',
                'idfv' => 'idfv-123',
                'idfa' => 'idfa-456',
                'google_ad_id' => 'google-789',
                'roku_ad_id' => 'roku-012',
            ],
            'expectedOutput' => [
                'model' => 'iPhone 14',
                'os' => 'iOS 16.0',
                'device_id' => 'abc123',
                'ad_tracking_enabled' => false,
                'carrier' => 'Verizon',
                'idfv' => 'idfv-123',
                'idfa' => 'idfa-456',
                'google_ad_id' => 'google-789',
                'roku_ad_id' => 'roku-012',
            ],
        ];

        yield 'explicit null carrier from API' => [
            'input' => [
                'model' => 'Pixel XL',
                'os' => 'Android (Q)',
                'carrier' => null,
                'device_id' => '312ef2c1-83db-4789-967-554545a1bf7a',
                'ad_tracking_enabled' => true,
            ],
            'expectedOutput' => [
                'model' => 'Pixel XL',
                'os' => 'Android (Q)',
                'device_id' => '312ef2c1-83db-4789-967-554545a1bf7a',
                'ad_tracking_enabled' => true,
            ],
        ];
    }

    /**
     * @dataProvider provideTestFromArrayAndToArrayData
     *
     * @param array<string, mixed> $input
     * @param array<string, mixed> $expectedOutput
     */
    public function testFromArrayAndToArray(array $input, array $expectedOutput): void
    {
        // Execution
        $device = Device::fromArray($input);
        $result = $device->toArray();

        // Assertion
        self::assertSame($input['model'], $device->model);
        self::assertSame($input['os'], $device->os);
        self::assertSame($input['device_id'], $device->deviceId);
        self::assertSame($input['ad_tracking_enabled'], $device->adTrackingEnabled);
        self::assertSame($expectedOutput, $result);
    }
}
