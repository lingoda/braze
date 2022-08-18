<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Object;

use Lingoda\BrazeBundle\Api\Object\Facebook;
use Lingoda\BrazeBundle\Tests\Api\SerializationTestCase;

final class FacebookSerializationTest extends SerializationTestCase
{
    /**
     * @dataProvider serializationData
     */
    public function testSerialization(Facebook $facebook, string $expectedJson): void
    {
        self::assertSame($expectedJson, $this->json($facebook));
    }

    /**
     * @return iterable<string, array{Facebook, string}>
     */
    public function serializationData(): iterable
    {
        yield 'id' => [
            new Facebook(['id' => 'facebook-id']),
            '{"id":"facebook-id"}',
        ];

        yield 'likes' => [
            new Facebook(['likes' => []]),
            '{"likes":[]}',
        ];

        yield 'num_friends' => [
            new Facebook(['num_friends' => 1]),
            '{"num_friends":1}',
        ];

        yield 'all' => [
            new Facebook([
                'id' => 'facebook-id',
                'likes' => [
                    'like1',
                    'like2',
                ],
                'num_friends' => 1,
            ]),
            '{"id":"facebook-id","likes":["like1","like2"],"num_friends":1}',
        ];
    }
}
