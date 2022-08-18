<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Response;

use Lingoda\BrazeBundle\Api\Exception\ApiKeyMissingOrUnknownException;
use Lingoda\BrazeBundle\Api\Exception\BadRequestException;
use Lingoda\BrazeBundle\Api\Response\ApiResponse;
use Lingoda\BrazeBundle\Api\Response\ApiResponseInterface;
use Lingoda\BrazeBundle\Api\Response\ApiResponseProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiResponseProcessorSpec extends ObjectBehavior
{
    function let(SerializerInterface $serializer, ResponseInterface $response, ApiResponseInterface $apiResponse)
    {
        $this->beConstructedWith($serializer);

        $response->getContent(Argument::cetera())->willReturn('content');
        $serializer
            ->deserialize('content', ApiResponseInterface::class, 'json')
            ->willReturn($apiResponse)
        ;
        $serializer
            ->deserialize('content', ApiResponse::class, 'json')
            ->willReturn($apiResponse)
        ;

        $apiResponse->getMessage()->willReturn('content');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ApiResponseProcessor::class);
    }

    function it_can_handle_queued_response(
        SerializerInterface $serializer,
        ResponseInterface $response,
        ApiResponse $apiResponseQueued
    ) {
        $response->getStatusCode()->willReturn(Response::HTTP_ACCEPTED);
        $serializer->deserialize('content', ApiResponse::class, 'json')->willReturn($apiResponseQueued);

        $this->process($response, ApiResponseInterface::class)->shouldBeAnInstanceOf(ApiResponse::class);
    }

    function it_can_handle_success_response(
        ResponseInterface $response,
        ApiResponseInterface $apiResponse
    ) {
        $response->getStatusCode()->willReturn(Response::HTTP_OK);

        $this->process($response, ApiResponseInterface::class)->shouldBeEqualTo($apiResponse);
    }

    function it_throws_expcetion_on_forbidden_request(
        ResponseInterface $response
    ) {
        $response->getStatusCode()->willReturn(Response::HTTP_FORBIDDEN);

        $this
            ->shouldThrow(ApiKeyMissingOrUnknownException::class)
            ->during('process', [$response, ApiResponseInterface::class])
        ;
    }

    function it_throws_expcetion_on_unauthorized_request(
        ResponseInterface $response
    ) {
        $response->getStatusCode()->willReturn(Response::HTTP_UNAUTHORIZED);

        $this
            ->shouldThrow(ApiKeyMissingOrUnknownException::class)
            ->during('process', [$response, ApiResponseInterface::class])
        ;
    }

    function it_throws_expcetion_on_not_found_request(
        ResponseInterface $response
    ) {
        $response->getStatusCode()->willReturn(Response::HTTP_NOT_FOUND);

        $this
            ->shouldThrow(ApiKeyMissingOrUnknownException::class)
            ->during('process', [$response, ApiResponseInterface::class])
        ;
    }

    function it_throws_expcetion_on_bad_request(
        ResponseInterface $response
    ) {
        $response->getStatusCode()->willReturn(Response::HTTP_BAD_REQUEST);

        $this
            ->shouldThrow(BadRequestException::class)
            ->during('process', [$response, ApiResponseInterface::class])
        ;
    }
}
