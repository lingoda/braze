<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\Canvas;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\Canvas
 */
final class CanvasTest extends TestCase
{
    /**
     * @return iterable<string, array{
     *     input: array<string, mixed>,
     *     expectedStepsCount: int|null
     * }>
     */
    public static function provideTestFromArrayAndToArrayData(): iterable
    {
        yield 'full API response with steps' => [
            'input' => [
                'name' => 'Non Global Holdout Group 4/21/21',
                'api_canvas_id' => '46972a9d-dc81-473f-aa03-e3473b4ed781',
                'last_received_message' => '2021-07-07T20:46:24.136Z',
                'last_entered' => '2021-07-07T20:45:24.000+00:00',
                'variation_name' => 'Variant 1',
                'in_control' => false,
                'last_exited' => '2021-07-07T20:46:24.136Z',
                'steps_received' => [
                    [
                        'name' => 'Step',
                        'api_canvas_step_id' => '43d1a349-c3c8-4be1-9fbe-ce708e4d1c39',
                        'last_received' => '2021-07-07T20:46:24.136Z',
                    ],
                ],
            ],
            'expectedStepsCount' => 1,
        ];

        yield 'required fields only' => [
            'input' => [
                'name' => 'Canvas Name',
                'api_canvas_id' => '46972a9d-dc81-473f-aa03-e3473b4ed781',
                'last_received_message' => '2021-07-07T20:46:24.136Z',
                'last_entered' => '2021-07-07T20:45:24.000+00:00',
                'last_exited' => '2021-07-07T20:46:24.136Z',
                'in_control' => true,
            ],
            'expectedStepsCount' => null,
        ];
    }

    /**
     * @dataProvider provideTestFromArrayAndToArrayData
     *
     * @param array<string, mixed> $input
     */
    public function testFromArrayAndToArray(array $input, ?int $expectedStepsCount): void
    {
        // Execution
        $canvas = Canvas::fromArray($input);
        $result = $canvas->toArray();

        // Assertion
        self::assertSame($input['name'], $canvas->name);
        self::assertSame($input['api_canvas_id'], $canvas->apiCanvasId);
        self::assertSame('2021-07-07', $canvas->lastReceivedMessage->format('Y-m-d'));
        self::assertSame('2021-07-07', $canvas->lastEntered->format('Y-m-d'));
        self::assertSame('2021-07-07', $canvas->lastExited->format('Y-m-d'));
        self::assertSame($input['in_control'], $canvas->inControl);
        self::assertSame($input['variation_name'] ?? null, $canvas->variationName);

        if ($expectedStepsCount !== null) {
            self::assertNotNull($canvas->stepsReceived);
            self::assertCount($expectedStepsCount, $canvas->stepsReceived);
            self::assertIsArray($result['steps_received']);
            self::assertCount($expectedStepsCount, $result['steps_received']);
        } else {
            self::assertNull($canvas->stepsReceived);
            self::assertArrayNotHasKey('steps_received', $result);
        }
    }

    /**
     * @return iterable<string, array{data: array<string, mixed>, expectedMessage: string}>
     */
    public static function provideTestFromArrayThrowsExceptionForInvalidDateData(): iterable
    {
        yield 'invalid last_received_message' => [
            'data' => [
                'name' => 'Canvas Name',
                'api_canvas_id' => '46972a9d-dc81-473f-aa03-e3473b4ed781',
                'last_received_message' => 'invalid-date',
                'last_entered' => '2021-07-07T20:45:24.000+00:00',
                'last_exited' => '2021-07-07T20:46:24.136Z',
                'in_control' => true,
            ],
            'expectedMessage' => 'Invalid date format for last_received_message',
        ];

        yield 'invalid last_entered' => [
            'data' => [
                'name' => 'Canvas Name',
                'api_canvas_id' => '46972a9d-dc81-473f-aa03-e3473b4ed781',
                'last_received_message' => '2021-07-07T20:46:24.136Z',
                'last_entered' => 'invalid-date',
                'last_exited' => '2021-07-07T20:46:24.136Z',
                'in_control' => true,
            ],
            'expectedMessage' => 'Invalid date format for last_entered',
        ];

        yield 'invalid last_exited' => [
            'data' => [
                'name' => 'Canvas Name',
                'api_canvas_id' => '46972a9d-dc81-473f-aa03-e3473b4ed781',
                'last_received_message' => '2021-07-07T20:46:24.136Z',
                'last_entered' => '2021-07-07T20:45:24.000+00:00',
                'last_exited' => 'invalid-date',
                'in_control' => true,
            ],
            'expectedMessage' => 'Invalid date format for last_exited',
        ];
    }

    /**
     * @dataProvider provideTestFromArrayThrowsExceptionForInvalidDateData
     *
     * @param array<string, mixed> $data
     */
    public function testFromArrayThrowsExceptionForInvalidDate(array $data, string $expectedMessage): void
    {
        // Expect
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        // Execution
        Canvas::fromArray($data);
    }
}
