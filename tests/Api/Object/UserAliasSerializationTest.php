<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Object;

use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Tests\Api\SerializationTestCase;

final class UserAliasSerializationTest extends SerializationTestCase
{
    /**
     * @dataProvider serializationData
     */
    public function testSerialization(UserAlias $userAlias, string $expectedJson): void
    {
        self::assertSame($expectedJson, $this->json($userAlias));
    }

    /**
     * @return iterable<string, array{UserAlias, string}>
     */
    public function serializationData(): iterable
    {
        yield 'default' => [
            new UserAlias('example_name', 'example_label'),
            '{"alias_name":"example_name","alias_label":"example_label"}',
        ];

        yield 'with-external-id' => [
            new UserAlias('example_name', 'example_label', new ExternalId('external-id')),
            '{"alias_name":"example_name","alias_label":"example_label","external_id":"external-id"}',
        ];
    }
}
