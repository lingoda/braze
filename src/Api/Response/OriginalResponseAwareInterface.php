<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Response;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface OriginalResponseAwareInterface
{
    public function getOriginalResponse(): ResponseInterface;

    public function setOriginalResponse(ResponseInterface $response): void;
}
