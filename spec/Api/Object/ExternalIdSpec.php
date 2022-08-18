<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Object;

use InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use PhpSpec\ObjectBehavior;

class ExternalIdSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('external-id');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ExternalId::class);
        $this->getValue()->shouldBeEqualTo('external-id');
    }

    function it_throws_exception_with_empty_id()
    {
        $this->beConstructedWith('');
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }
}
