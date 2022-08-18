<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Object;

use InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\BrazeId;
use PhpSpec\ObjectBehavior;

class BrazeIdSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('braze-id');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BrazeId::class);
        $this->getValue()->shouldBeEqualTo('braze-id');
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
