<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Object;

use InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Properties;
use PhpSpec\ObjectBehavior;

class PropertiesSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Properties::class);
        $this->count()->shouldBe(0);
        $this->isEmpty()->shouldBe(true);
    }

    function it_throws_exception_during_initialization_with_invalid_property_type()
    {
        $this
            ->shouldThrow(new InvalidArgumentException(
                'Property names must be non-empty strings less than or equal to 255 characters'
            ))
            ->during('__construct', [[1 => 'value']])
        ;
    }

    function it_throws_exception_during_initialization_with_empty_property()
    {
        $this
            ->shouldThrow(new InvalidArgumentException(
                'Property names must be non-empty strings'
            ))
            ->during('__construct', [['' => 'value']])
        ;
    }

    function it_throws_exception_during_initialization_with_property_containing_dollar_sign()
    {
        $this
            ->shouldThrow(new InvalidArgumentException(
                'Property names must be non-empty strings with no leading dollar signs'
            ))
            ->during('__construct', [['$property' => 'value']])
        ;
    }

    function it_throws_exception_during_initialization_with_property_length_greater_than_255_char()
    {
        $this
            ->shouldThrow(new InvalidArgumentException(
                'Property must be less than or equal to 255 characters'
            ))
            ->during('__construct', [[str_repeat('a', 256) => 'value']])
        ;
    }

    function it_throws_exception_with_invalid_property_type()
    {
        $this
            ->shouldThrow(new InvalidArgumentException(
                'Property names must be non-empty strings less than or equal to 255 characters'
            ))
            ->during('offsetSet', [1, 'value'])
        ;
    }

    function it_throws_exception_with_empty_property()
    {
        $this
            ->shouldThrow(new InvalidArgumentException(
                'Property names must be non-empty strings'
            ))
            ->during('offsetSet', ['', 'value'])
        ;
    }

    function it_throws_exception_with_property_containing_dollar_sign()
    {
        $this
            ->shouldThrow(new InvalidArgumentException(
                'Property names must be non-empty strings with no leading dollar signs'
            ))
            ->during('offsetSet', ['$property', 'value'])
        ;
    }

    function it_throws_exception_with_property_length_greater_than_255_char()
    {
        $this
            ->shouldThrow(new InvalidArgumentException(
                'Property must be less than or equal to 255 characters'
            ))
            ->during('offsetSet', [str_repeat('a', 256), 'value'])
        ;
    }

    function it_can_be_created()
    {
        $this->beConstructedWith(['property' => 'value']);
        $this->count()->shouldBe(1);
        $this->isEmpty()->shouldBe(false);
        $this->jsonSerialize()->shouldBeEqualTo([
            'property' => 'value',
        ]);
        $this->offsetGet('property')->shouldBeEqualTo('value');
    }
}
