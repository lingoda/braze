<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Object;

use Lingoda\BrazeBundle\Api\Object\CampaignId;
use Lingoda\BrazeBundle\Api\Object\Identifier;
use Lingoda\BrazeBundle\Api\Object\SendId;
use Lingoda\BrazeBundle\Tests\Api\SerializationTestCase;

final class IdSerializationTest extends SerializationTestCase
{
    /**
     * @dataProvider itemSerializationData
     */
    public function testItemSerialization(Identifier $identifier, string $expectedJson): void
    {
        self::assertSame($expectedJson, $this->json($identifier));
    }

    /**
     * @dataProvider nodeSerializationData
     */
    public function testNodeSerialization(object $identifier, string $expectedJson): void
    {
        self::assertSame($expectedJson, $this->json($identifier));
    }

    /**
     * @return iterable<string, array{Identifier, string}>
     */
    public function itemSerializationData(): iterable
    {
        yield 'campaign id' => [
            new CampaignId('some_campaign_id'),
            '"some_campaign_id"',
        ];

        yield 'send id' => [
            new SendId('some_send_id'),
            '"some_send_id"',
        ];
    }

    /**
     * @return iterable<string, array{\stdClass, string}>
     */
    public function nodeSerializationData(): iterable
    {
        yield 'campaign id' => [
            (object) ['campaign_id' => new CampaignId('some_campaign_id')],
            '{"campaign_id":"some_campaign_id"}',
        ];

        yield 'send id' => [
            (object) ['send_id' => new SendId('some_send_id')],
            '{"send_id":"some_send_id"}',
        ];
    }
}
