<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api;

use Lingoda\BrazeBundle\Api\BrazeClient;
use Lingoda\BrazeBundle\Api\BrazeSerializerInterface;
use Lingoda\BrazeBundle\Api\Response\ApiResponse;
use Lingoda\BrazeBundle\Api\Response\ApiResponseInterface;
use Lingoda\BrazeBundle\Api\Response\ApiResponseProcessorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class BrazeClientSpec extends ObjectBehavior
{
    function let(
        LoggerInterface $logger,
        BrazeSerializerInterface $serializer,
        ApiResponseProcessorInterface $responseProcessor,
        ApiResponseInterface $apiResponse,
        HttpClientInterface $httpClient,
        ResponseInterface $response
    ) {
        $this->beConstructedWith(
            $logger,
            $serializer,
            $responseProcessor,
            $httpClient
        );

        $httpClient->request(Argument::any(), Argument::any(), Argument::any())->willReturn($response);
        $httpClient->request(Argument::any(), Argument::any())->willReturn($response);

        $response->getContent(false)->willReturn('Braze response');
        $response->getInfo('debug')->willReturn(null);

        $responseProcessor->process(Argument::type(ResponseInterface::class), Argument::any())->willReturn($apiResponse);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BrazeClient::class);
    }

    function it_can_post(
        HttpClientInterface $httpClient,
        ResponseInterface $response,
        BrazeSerializerInterface $serializer,
        ApiResponseProcessorInterface $responseProcessor
    ) {
        $serializer
            ->serialize(['foo' => 'bar'])
            ->willReturn('serialized-foo-bar')
            ->shouldBeCalledOnce()
        ;

        $httpClient
            ->request(
                'POST',
                '/post-test',
                Argument::that(fn (
                    array $options
                ): bool => isset($options['body']) && $options['body'] === 'serialized-foo-bar')
            )
            ->willReturn($response)
            ->shouldBeCalledOnce()
        ;

        $responseProcessor->process($response, ApiResponse::class)->shouldBeCalledOnce();

        $this->post('/post-test', [
            'foo' => 'bar',
        ]);
    }

    function it_can_get(
        HttpClientInterface $httpClient,
        ResponseInterface $response,
        ApiResponseProcessorInterface $responseProcessor
    ) {
        $httpClient
            ->request(
                'GET',
                '/get-test',
                Argument::that(fn (
                    array $options
                ): bool => isset($options['query']) && $options['query'] === ['foo' => 'bar'])
            )
            ->willReturn($response)
            ->shouldBeCalledOnce()
        ;

        $responseProcessor->process($response, ApiResponse::class)->shouldBeCalledOnce();

        $this->get('/get-test', ['foo' => 'bar']);
    }

    function it_can_delete(
        HttpClientInterface $httpClient,
        ResponseInterface $response,
        BrazeSerializerInterface $serializer,
        ApiResponseProcessorInterface $responseProcessor
    ) {
        $serializer
            ->serialize(['foo' => 'bar'])
            ->willReturn('serialized-foo-bar')
            ->shouldBeCalledOnce()
        ;

        $httpClient
            ->request(
                'DELETE',
                '/delete-test',
                Argument::that(fn (
                    array $options
                ): bool => isset($options['body']) && $options['body'] === 'serialized-foo-bar')
            )
            ->willReturn($response)
            ->shouldBeCalledOnce()
        ;

        $responseProcessor->process($response, ApiResponse::class)->shouldBeCalledOnce();

        $this->delete('/delete-test', ['foo' => 'bar']);
    }
}
