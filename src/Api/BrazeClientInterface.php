<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api;

use Lingoda\BrazeBundle\Api\Exception\HttpException;
use Lingoda\BrazeBundle\Api\Response\ApiResponse;
use Lingoda\BrazeBundle\Api\Response\ApiResponseInterface;

interface BrazeClientInterface
{
    /**
     * Customers using Braze’s EU database should use the below endpoint.
     */
    public const DEFAULT_BASE_URI = 'https://rest.fra-01.braze.eu/';

    /**
     * @param array<string, mixed>               $options
     * @param class-string<ApiResponseInterface> $apiResponseClass
     *
     * @throws HttpException
     */
    public function delete(
        string $url,
        array $options = [],
        string $apiResponseClass = ApiResponse::class
    ): ApiResponseInterface;

    /**
     * @param array<string, mixed>               $options
     * @param class-string<ApiResponseInterface> $apiResponseClass
     *
     * @throws HttpException
     */
    public function get(
        string $url,
        array $options = [],
        string $apiResponseClass = ApiResponse::class
    ): ApiResponseInterface;

    /**
     * @param array<string, mixed>               $options
     * @param class-string<ApiResponseInterface> $apiResponseClass
     *
     * @throws HttpException
     */
    public function post(
        string $url,
        array $options = [],
        string $apiResponseClass = ApiResponse::class
    ): ApiResponseInterface;
}
