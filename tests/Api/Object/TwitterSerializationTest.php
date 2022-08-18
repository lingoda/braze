<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Object;

use Lingoda\BrazeBundle\Api\Object\Twitter;
use Lingoda\BrazeBundle\Tests\Api\SerializationTestCase;

final class TwitterSerializationTest extends SerializationTestCase
{
    /**
     * @dataProvider serializationData
     */
    public function testSerialization(Twitter $twitter, string $expectedJson): void
    {
        self::assertSame($expectedJson, $this->json($twitter));
    }

    /**
     * @return iterable<string, array{Twitter, string}>
     */
    public function serializationData(): iterable
    {
        yield 'id' => [
            new Twitter(['id' => 1]),
            '{"id":1}',
        ];

        yield 'screen_name' => [
            new Twitter(['screen_name' => 'screen-name']),
            '{"screen_name":"screen-name"}',
        ];

        yield 'followers_count' => [
            new Twitter(['followers_count' => 1]),
            '{"followers_count":1}',
        ];

        yield 'friends_count' => [
            new Twitter(['friends_count' => 2]),
            '{"friends_count":2}',
        ];

        yield 'statuses_count' => [
            new Twitter(['statuses_count' => 3]),
            '{"statuses_count":3}',
        ];

        yield 'all' => [
            new Twitter([
                'id' => 1,
                'screen_name' => 'screen-name',
                'followers_count' => 2,
                'friends_count' => 3,
                'statuses_count' => 4,
            ]),
            '{"id":1,"screen_name":"screen-name","followers_count":2,"friends_count":3,"statuses_count":4}',
        ];
    }
}
