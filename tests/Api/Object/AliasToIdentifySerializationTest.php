<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Object;

use Lingoda\BrazeBundle\Api\Object\AliasToIdentify;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Tests\Api\SerializationTestCase;

final class AliasToIdentifySerializationTest extends SerializationTestCase
{
    /**
     * @dataProvider serializationData
     */
    public function testSerialization(AliasToIdentify $aliasToIdentify, string $expectedJson): void
    {
        self::assertSame($expectedJson, $this->json($aliasToIdentify));
    }

    /**
     * @return iterable<string, array{AliasToIdentify, string}>
     */
    public function serializationData(): iterable
    {
        yield 'id' => [
            new AliasToIdentify(new ExternalId('external-id'), new UserAlias('name', 'label')),
            '{"external_id":"external-id","user_alias":{"alias_name":"name","alias_label":"label"}}',
        ];
    }
}
