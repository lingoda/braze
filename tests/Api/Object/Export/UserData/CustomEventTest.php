<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\CustomEvent;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\CustomEvent
 */
final class CustomEventTest extends TestCase
{
    public function testFromArrayAndToArrayRoundTrip(): void
    {
        // Setup
        $data = [
            'name' => 'Loyalty Acknowledgement',
            'first' => '2021-06-28T17:02:43.032Z',
            'last' => '2021-06-28T17:02:43.032Z',
            'count' => 1,
        ];

        // Execution
        $event = CustomEvent::fromArray($data);
        $result = $event->toArray();

        // Assertion
        self::assertSame('Loyalty Acknowledgement', $event->name);
        self::assertSame('2021-06-28', $event->first->format('Y-m-d'));
        self::assertSame('2021-06-28', $event->last->format('Y-m-d'));
        self::assertSame(1, $event->count);
        self::assertSame('2021-06-28T17:02:43.032+00:00', $result['first']);
        self::assertSame('2021-06-28T17:02:43.032+00:00', $result['last']);
    }

    /**
     * @return iterable<string, array{data: array<string, mixed>, expectedMessage: string}>
     */
    public static function provideTestFromArrayThrowsExceptionForInvalidDateData(): iterable
    {
        yield 'invalid first' => [
            'data' => [
                'name' => 'Loyalty Acknowledgement',
                'first' => 'invalid-date',
                'last' => '2021-06-28T17:02:43.032Z',
                'count' => 1,
            ],
            'expectedMessage' => 'Invalid date format for first',
        ];

        yield 'invalid last' => [
            'data' => [
                'name' => 'Loyalty Acknowledgement',
                'first' => '2021-06-28T17:02:43.032Z',
                'last' => 'invalid-date',
                'count' => 1,
            ],
            'expectedMessage' => 'Invalid date format for last',
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
        CustomEvent::fromArray($data);
    }
}
