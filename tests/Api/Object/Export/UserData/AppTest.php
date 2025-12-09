<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\App;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\App
 */
final class AppTest extends TestCase
{
    public function testFromArrayAndToArrayRoundTrip(): void
    {
        // Setup
        $data = [
            'name' => 'MovieCannon',
            'platform' => 'Android',
            'version' => '3.29.0',
            'sessions' => 1129,
            'first_used' => '2020-02-02T19:56:19.142Z',
            'last_used' => '2021-11-11T00:25:19.201Z',
        ];

        // Execution
        $app = App::fromArray($data);
        $result = $app->toArray();

        // Assertion
        self::assertSame('MovieCannon', $app->name);
        self::assertSame('Android', $app->platform);
        self::assertSame('3.29.0', $app->version);
        self::assertSame(1129, $app->sessions);
        self::assertSame('2020-02-02', $app->firstUsed->format('Y-m-d'));
        self::assertSame('2021-11-11', $app->lastUsed->format('Y-m-d'));
        self::assertSame('2020-02-02T19:56:19.142+00:00', $result['first_used']);
        self::assertSame('2021-11-11T00:25:19.201+00:00', $result['last_used']);
    }

    /**
     * @return iterable<string, array{data: array<string, mixed>, expectedMessage: string}>
     */
    public static function provideTestFromArrayThrowsExceptionForInvalidDateData(): iterable
    {
        yield 'invalid first_used' => [
            'data' => [
                'name' => 'MovieCannon',
                'platform' => 'Android',
                'version' => '3.29.0',
                'sessions' => 1129,
                'first_used' => 'invalid-date',
                'last_used' => '2021-11-11T00:25:19.201Z',
            ],
            'expectedMessage' => 'Invalid date format for first_used',
        ];

        yield 'invalid last_used' => [
            'data' => [
                'name' => 'MovieCannon',
                'platform' => 'Android',
                'version' => '3.29.0',
                'sessions' => 1129,
                'first_used' => '2020-02-02T19:56:19.142Z',
                'last_used' => 'invalid-date',
            ],
            'expectedMessage' => 'Invalid date format for last_used',
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
        App::fromArray($data);
    }
}
