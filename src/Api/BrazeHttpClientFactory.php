<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Retry\GenericRetryStrategy;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class BrazeHttpClientFactory
{
    public static function create(
        string $apiKey,
        string $baseUri,
        int $maxRetries
    ): HttpClientInterface {
        return ScopingHttpClient::forBaseUri(
            new RetryableHttpClient(
                HttpClient::create(),
                new GenericRetryStrategy(),
                $maxRetries
            ),
            $baseUri,
            [
                'auth_bearer' => $apiKey,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]
        );
    }
}
