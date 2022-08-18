<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Object;

use Lingoda\BrazeBundle\Api\Object\CanvasEntryProperties;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Recipient;
use Lingoda\BrazeBundle\Api\Object\TriggerProperties;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Tests\Api\SerializationTestCase;

final class RecipientSerializationTest extends SerializationTestCase
{
    /**
     * @dataProvider serializationData
     */
    public function testSerialization(Recipient $recipient, string $expectedJson): void
    {
        self::assertSame($expectedJson, $this->json($recipient));
    }

    /**
     * @return iterable<string, array{Recipient, string}>
     */
    public function serializationData(): iterable
    {
        yield 'with external user id' => [
            new Recipient(['external_user_id' => new ExternalId('user_id')]),
            '{"external_user_id":"user_id"}',
        ];

        yield 'with user alias' => [
            new Recipient(['user_alias' => new UserAlias('name', 'label')]),
            '{"user_alias":{"alias_name":"name","alias_label":"label"}}',
        ];

        yield 'with all properties' => [
            new Recipient([
                'external_user_id' => new ExternalId('user_id'),
                'trigger_properties' => new TriggerProperties(['key' => 'value']),
                'canvas_entry_properties' => new CanvasEntryProperties(['c_key' => 'c_value']),
            ]),
            '{"external_user_id":"user_id","trigger_properties":{"key":"value"},"canvas_entry_properties":{"c_key":"c_value"}}',
        ];
    }
}
