<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Response;

use Lingoda\BrazeBundle\Api\Response\ApiResponse;
use Lingoda\BrazeBundle\Api\Response\ApiResponseInterface;
use PhpSpec\ObjectBehavior;

class ApiResponseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(ApiResponseInterface::MESSAGE_SUCCESS);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ApiResponse::class);
        $this->isSuccess()->shouldBeEqualTo(true);
        $this->hasErrors()->shouldBeEqualTo(false);
        $this->isQueued()->shouldBeEqualTo(false);
        $this->isFatal()->shouldBeEqualTo(false);
        $this->getMessage()->shouldBeEqualTo(ApiResponseInterface::MESSAGE_SUCCESS);
    }

    function it_can_have_non_fatal_errors()
    {
        $errors = ['some-error-message'];

        $this->beConstructedWith(ApiResponseInterface::MESSAGE_SUCCESS, $errors);
        $this->isSuccess()->shouldBeEqualTo(true);
        $this->hasErrors()->shouldBeEqualTo(true);
        $this->getErrors()->shouldBeEqualTo($errors);
    }

    function it_can_have_fatal_error()
    {
        $this->beConstructedWith('Fatal error message');
        $this->isFatal()->shouldBeEqualTo(true);
    }

    function it_can_be_queued()
    {
        $this->beConstructedWith(ApiResponseInterface::MESSAGE_QUEUED);
        $this->isQueued()->shouldBeEqualTo(true);
    }
}
