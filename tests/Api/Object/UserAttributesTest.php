<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Object;

use Lingoda\BrazeBundle\Api\Object\BrazeId;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use PHPUnit\Framework\TestCase;

final class UserAttributesTest extends TestCase
{
    /**
     * @dataProvider hasAttributesData
     */
    public function testHasAttributes(UserAttributes $userAttributes, bool $expected): void
    {
        self::assertSame($expected, $userAttributes->hasAttributes());
    }

    /**
     * @return iterable<string, array{UserAttributes, bool}>
     */
    public function hasAttributesData(): iterable
    {
        yield 'braze-id' => [
            new UserAttributes(['braze_id' => new BrazeId('b')]),
            false,
        ];

        yield 'external-id' => [
            new UserAttributes(['external_id' => new ExternalId('1')]),
            false,
        ];

        yield 'user-alias' => [
            new UserAttributes(['user_alias' => new UserAlias('a', 'b')]),
            false,
        ];

        yield 'update' => [
            new UserAttributes([
                'external_id' => new ExternalId('1'),
                '_update_existing_only' => true,
            ]),
            false,
        ];

        yield 'at-least-on-property' => [
            new UserAttributes([
                'external_id' => new ExternalId('1'),
                '_update_existing_only' => true,
                'first_name' => 'John',
            ]),
            true,
        ];
    }
}
