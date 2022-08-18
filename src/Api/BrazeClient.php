<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api;

use Lingoda\BrazeBundle\Api\Exception\ApiErrorResponseException;
use Lingoda\BrazeBundle\Api\Exception\HttpException;
use Lingoda\BrazeBundle\Api\Response\ApiResponse;
use Lingoda\BrazeBundle\Api\Response\ApiResponseInterface;
use Lingoda\BrazeBundle\Api\Response\ApiResponseProcessorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Braze Client for the REST API.
 *
 * @see https://www.braze.com/docs/api/basics/
 */
final class BrazeClient implements BrazeClientInterface
{
    private LoggerInterface $logger;
    private HttpClientInterface $httpClient;
    private BrazeSerializerInterface $serializer;
    private ApiResponseProcessorInterface $responseProcessor;

    public function __construct(
        LoggerInterface $logger,
        BrazeSerializerInterface $serializer,
        ApiResponseProcessorInterface $responseProcessor,
        HttpClientInterface $httpClient
    ) {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->responseProcessor = $responseProcessor;
        $this->httpClient = $httpClient;
    }

    /**
     * @param array<string, mixed> $options
     * @param class-string<ApiResponseInterface> $apiResponseClass
     *
     * @throws Exception\ApiKeyMissingOrUnknownException
     * @throws Exception\BadRequestException
     * @throws HttpException
     */
    public function delete(
        string $url,
        array $options = [],
        string $apiResponseClass = ApiResponse::class
    ): ApiResponseInterface {
        return $this->request(Request::METHOD_DELETE, $url, [
            'body' => $options,
        ], $apiResponseClass);
    }

    /**
     * @param array<string, mixed> $options
     * @param class-string<ApiResponseInterface> $apiResponseClass
     *
     * @throws Exception\ApiKeyMissingOrUnknownException
     * @throws Exception\BadRequestException
     * @throws HttpException
     */
    public function get(
        string $url,
        array $options = [],
        string $apiResponseClass = ApiResponse::class
    ): ApiResponseInterface {
        return $this->request(Request::METHOD_GET, $url, [
            'query' => $options,
        ], $apiResponseClass);
    }

    /**
     * @param array<string, mixed> $options
     * @param class-string<ApiResponseInterface> $apiResponseClass
     *
     * @throws Exception\ApiKeyMissingOrUnknownException
     * @throws Exception\BadRequestException
     * @throws HttpException
     */
    public function post(
        string $url,
        array $options = [],
        string $apiResponseClass = ApiResponse::class
    ): ApiResponseInterface {
        return $this->request(Request::METHOD_POST, $url, [
            'body' => $options,
        ], $apiResponseClass);
    }

    /**
     * @param Request::METHOD_* $method
     * @param array<string, mixed> $data
     * @param class-string<ApiResponseInterface> $apiResponseClass
     *
     * @throws ApiErrorResponseException
     * @throws HttpException
     */
    private function request(
        string $method,
        string $url,
        array $data = [],
        string $apiResponseClass = ApiResponse::class
    ): ApiResponseInterface {
        if (\array_key_exists('body', $data)) {
            $data['body'] = $this->serializer->serialize($data['body']);
        }

        try {
            $response = $this->httpClient->request($method, $url, $data);

            $this->logger->info(self::class . '::request', [
                'url' => $url,
                'method' => $method,
                'body' => $data['body'] ?? null,
                'query' => $data['query'] ?? null,
                'response' => $response->getContent(false),
                'debug' => $response->getInfo('debug'),
            ]);

            return $this->responseProcessor->process($response, $apiResponseClass);
        } catch (ExceptionInterface $e) {
            throw HttpException::create($e);
        }
    }
}
