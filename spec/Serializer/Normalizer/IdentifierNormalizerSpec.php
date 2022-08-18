<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Serializer\Normalizer;

use Lingoda\BrazeBundle\Api\Object\IdentifierInterface;
use Lingoda\BrazeBundle\Serializer\Normalizer\IdentifierNormalizer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class IdentifierNormalizerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IdentifierNormalizer::class);
    }

    function it_supports_identifier_normalization_only(IdentifierInterface $identifier)
    {
        $this->supportsNormalization($identifier)->shouldBeEqualTo(true);

        $this->supportsNormalization([])->shouldBeEqualTo(false);
        $this->supportsNormalization('')->shouldBeEqualTo(false);
        $this->supportsNormalization(12)->shouldBeEqualTo(false);
        $this->supportsNormalization(0)->shouldBeEqualTo(false);
        $this->supportsNormalization(0.1)->shouldBeEqualTo(false);
        $this->supportsNormalization(new \stdClass())->shouldBeEqualTo(false);
        $this->supportsNormalization(true)->shouldBeEqualTo(false);
        $this->supportsNormalization(false)->shouldBeEqualTo(false);
    }

    function it_can_normalize_identifier(IdentifierInterface $identifier, IdentifierInterface $identifier2)
    {
        $identifier2->getValue()->willReturn('value')->shouldBeCalledOnce();
        $identifier
            ->getValue()
            ->willReturn('id', ['internal' => $identifier2->getWrappedObject()])
            ->shouldBeCalledTimes(2)
        ;

        $this->normalize($identifier)->shouldBeEqualTo('id');
        $this->normalize($identifier)->shouldBeEqualTo(['internal' => 'value']);
    }

    function it_throws_exception_on_invalid_type_normalization()
    {
        $this
            ->shouldThrow(
                new InvalidArgumentException(sprintf('The object must implement the %s interface', IdentifierInterface::class))
            )
            ->during('normalize', [new \stdClass()])
        ;
    }
}
