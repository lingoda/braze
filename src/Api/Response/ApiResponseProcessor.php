<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Response;

use Lingoda\BrazeBundle\Api\Exception\ApiErrorResponseException;
use Lingoda\BrazeBundle\Api\Exception\ApiKeyMissingOrUnknownException;
use Lingoda\BrazeBundle\Api\Exception\BadRequestException;
use Lingoda\BrazeBundle\Api\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ApiResponseProcessor implements ApiResponseProcessorInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function process(
        ResponseInterface $response,
        string $apiResponseClass
    ): ApiResponseInterface {
        try {
            switch ($response->getStatusCode()) {
                case Response::HTTP_ACCEPTED:
                    $apiResponseClass = ApiResponse::class;
                    break;

                case Response::HTTP_OK:
                case Response::HTTP_CREATED:
                    // serialize response to the defined class
                    break;

                case Response::HTTP_BAD_REQUEST:
                    throw BadRequestException::fromResponse($response);
                case Response::HTTP_UNAUTHORIZED:
                case Response::HTTP_FORBIDDEN:
                case Response::HTTP_NOT_FOUND:
                    throw ApiKeyMissingOrUnknownException::fromResponse($response);
                default:
                    throw ApiErrorResponseException::fromResponse($response);
            }

            $apiResponse = $this->getResponseContent($response, $apiResponseClass);
            if ($apiResponse instanceof OriginalResponseAwareInterface) {
                $apiResponse->setOriginalResponse($response);
            }

            return $apiResponse;
        } catch (HttpExceptionInterface $e) {
            throw HttpException::create($e);
        }
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getResponseContent(ResponseInterface $response, string $apiResponseClass): ApiResponseInterface
    {
        return $this->serializer->deserialize($response->getContent(), $apiResponseClass, 'json');
    }
}
