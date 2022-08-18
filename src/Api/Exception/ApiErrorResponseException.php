<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Exception;

use JsonException;
use Lingoda\BrazeBundle\Api\OriginalResponseAwareTrait;
use Lingoda\BrazeBundle\Api\Response\OriginalResponseAwareInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class ApiErrorResponseException extends \RuntimeException implements BrazeApiException, OriginalResponseAwareInterface
{
    use OriginalResponseAwareTrait;

    final protected function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return static
     */
    public static function fromResponse(ResponseInterface $response): self
    {
        try {
            $responseJson = $response->getContent(false);
            $apiResponse = $responseJson ? json_decode($responseJson, true, 512, \JSON_THROW_ON_ERROR) : [];
        } catch (ExceptionInterface|JsonException $e) {
            $apiResponse = [];
        }

        $message = \array_key_exists('message', $apiResponse) ? $apiResponse['message'] : 'Failed Braze API call';

        $instance = new static($message);
        $instance->setOriginalResponse($response);

        return $instance;
    }
}
