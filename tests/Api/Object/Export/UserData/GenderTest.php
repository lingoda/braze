<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Object\Export\UserData\Gender;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\Gender
 */
final class GenderTest extends TestCase
{
    /**
     * @return iterable<string, array{value: string, expected: Gender}>
     */
    public static function provideTestFromValidApiValueData(): iterable
    {
        yield 'male' => ['value' => 'M', 'expected' => Gender::Male];
        yield 'female' => ['value' => 'F', 'expected' => Gender::Female];
        yield 'other' => ['value' => 'O', 'expected' => Gender::Other];
        yield 'not applicable' => ['value' => 'N', 'expected' => Gender::NotApplicable];
        yield 'prefer not to say' => ['value' => 'P', 'expected' => Gender::PreferNotToSay];
        yield 'unknown (nil)' => ['value' => 'nil', 'expected' => Gender::Unknown];
    }

    /**
     * @dataProvider provideTestFromValidApiValueData
     *
     * @throws \TypeError
     * @throws \ValueError
     */
    public function testFromValidApiValue(string $value, Gender $expected): void
    {
        // Execution
        $gender = Gender::from($value);

        // Assertion
        self::assertSame($expected, $gender);
        self::assertSame($value, $gender->value);
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        // Execution & Assertion
        self::assertNull(Gender::tryFrom('invalid'));
    }
}
