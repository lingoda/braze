<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\Purchase;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\Purchase
 */
final class PurchaseTest extends TestCase
{
    public function testFromArrayAndToArrayRoundTrip(): void
    {
        // Setup
        $data = [
            'name' => 'item_40834',
            'first' => '2021-09-05T03:45:50.540Z',
            'last' => '2022-06-03T17:30:41.201Z',
            'count' => 10,
        ];

        // Execution
        $purchase = Purchase::fromArray($data);
        $result = $purchase->toArray();

        // Assertion
        self::assertSame('item_40834', $purchase->name);
        self::assertSame('2021-09-05', $purchase->first->format('Y-m-d'));
        self::assertSame('2022-06-03', $purchase->last->format('Y-m-d'));
        self::assertSame(10, $purchase->count);
        self::assertSame('2021-09-05T03:45:50.540+00:00', $result['first']);
        self::assertSame('2022-06-03T17:30:41.201+00:00', $result['last']);
    }

    /**
     * @return iterable<string, array{data: array<string, mixed>, expectedMessage: string}>
     */
    public static function provideTestFromArrayThrowsExceptionForInvalidDateData(): iterable
    {
        yield 'invalid first' => [
            'data' => [
                'name' => 'item_40834',
                'first' => 'invalid-date',
                'last' => '2022-06-03T17:30:41.201Z',
                'count' => 10,
            ],
            'expectedMessage' => 'Invalid date format for first',
        ];

        yield 'invalid last' => [
            'data' => [
                'name' => 'item_40834',
                'first' => '2021-09-05T03:45:50.540Z',
                'last' => 'invalid-date',
                'count' => 10,
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
        Purchase::fromArray($data);
    }
}
