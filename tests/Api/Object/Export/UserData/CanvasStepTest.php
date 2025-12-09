<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\CanvasStep;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\CanvasStep
 */
final class CanvasStepTest extends TestCase
{
    public function testFromArrayAndToArrayRoundTrip(): void
    {
        // Setup
        $data = [
            'name' => 'Step',
            'api_canvas_step_id' => '43d1a349-c3c8-4be1-9fbe-ce708e4d1c39',
            'last_received' => '2021-07-07T20:46:24.136Z',
        ];

        // Execution
        $step = CanvasStep::fromArray($data);
        $result = $step->toArray();

        // Assertion
        self::assertSame('Step', $step->name);
        self::assertSame('43d1a349-c3c8-4be1-9fbe-ce708e4d1c39', $step->apiCanvasStepId);
        self::assertSame('2021-07-07', $step->lastReceived->format('Y-m-d'));
        self::assertSame('2021-07-07T20:46:24.136+00:00', $result['last_received']);
    }

    public function testFromArrayThrowsExceptionForInvalidLastReceivedDate(): void
    {
        // Setup
        $data = [
            'name' => 'Step',
            'api_canvas_step_id' => '43d1a349-c3c8-4be1-9fbe-ce708e4d1c39',
            'last_received' => 'invalid-date',
        ];

        // Expect
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date format for last_received');

        // Execution
        CanvasStep::fromArray($data);
    }
}
