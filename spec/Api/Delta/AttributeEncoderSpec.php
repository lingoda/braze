<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Delta;

use Lingoda\BrazeBundle\Api\Delta\AttributeEncoder;
use Lingoda\BrazeBundle\Api\Object\Facebook;
use Lingoda\BrazeBundle\Api\Object\Location;
use Lingoda\BrazeBundle\Api\Object\Twitter;
use PhpSpec\ObjectBehavior;

class AttributeEncoderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AttributeEncoder::class);
    }

    function it_can_encode()
    {
        $this->encode([
            'string' => 'value',
            'int' => 1,
            'float' => 0.1,
            'bool' => true,
            'string_array' => ['one', 'two'],
            'facebook' => new Facebook(['id' => 'facebook-id']),
            'twitter' => new Twitter(['id' => 1]),
            'null' => null,
            'location' => new Location(12, 13),
        ])->shouldBeEqualTo([
            'string' => hash('sha256', 'value'),
            'int' => hash('sha256', (string) 1),
            'float' => hash('sha256', (string) 0.1),
            'bool' => hash('sha256', (string) true),
            'string_array' => [
                hash('sha256', 'one'),
                hash('sha256', 'two'),
            ],
            'facebook' => [
                'id' => hash('sha256', 'facebook-id'),
            ],
            'twitter' => [
                'id' => hash('sha256', (string) 1),
            ],
            'null' => hash('sha256', (string) null),
            'location' => hash('sha256', '12,13'),
        ]);
    }
}
