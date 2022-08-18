<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Object;

use InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\AppId;
use PhpSpec\ObjectBehavior;

class AppIdSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('f995d9aa-2be4-4f28-8b21-5ba0d03d85a8');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AppId::class);
        $this->getValue()->shouldBeEqualTo('f995d9aa-2be4-4f28-8b21-5ba0d03d85a8');
    }

    function it_throws_exception_on_invalid_id()
    {
        $this->beConstructedWith('non-uuid');
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }
}
