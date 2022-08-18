<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Response;

use Lingoda\BrazeBundle\Api\Response\ApiUserDeleteResponse;
use PhpSpec\ObjectBehavior;

class ApiUserDeleteResponseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('success', 1);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ApiUserDeleteResponse::class);
        $this->getDeleted()->shouldBeEqualTo(1);
        $this->getMessage()->shouldBeEqualTo('success');
        $this->getErrors()->shouldBeEqualTo([]);
    }

    function it_can_initialize_with_errors()
    {
        $this->beConstructedWith('success', 1, ['error']);
        $this->shouldHaveType(ApiUserDeleteResponse::class);
        $this->getDeleted()->shouldBeEqualTo(1);
        $this->getMessage()->shouldBeEqualTo('success');
        $this->getErrors()->shouldBeEqualTo(['error']);
    }
}
