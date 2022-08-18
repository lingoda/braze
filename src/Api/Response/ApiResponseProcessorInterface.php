<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Response;

use Lingoda\BrazeBundle\Api\Exception\ApiErrorResponseException;
use Lingoda\BrazeBundle\Api\Exception\HttpException;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface ApiResponseProcessorInterface
{
    /**
     * @param class-string<ApiResponseInterface> $apiResponseClass
     *
     * @throws ApiErrorResponseException
     * @throws HttpException
     */
    public function process(ResponseInterface $response, string $apiResponseClass): ApiResponseInterface;
}
