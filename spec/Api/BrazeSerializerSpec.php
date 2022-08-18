<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api;

use Lingoda\BrazeBundle\Api\BrazeSerializer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Serializer\SerializerInterface;

class BrazeSerializerSpec extends ObjectBehavior
{
    function let(SerializerInterface $serializer)
    {
        $this->beConstructedWith($serializer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BrazeSerializer::class);
    }

    function it_can_serialize_with_correct_context(SerializerInterface $serializer)
    {
        $serializer->serialize([], 'json', BrazeSerializer::DEFAULT_API_CONTEXT)->willReturn('{}')->shouldBeCalledOnce();

        $this->serialize([])->shouldBeEqualTo('{}');
    }
}
