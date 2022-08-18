<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait OriginalResponseAwareTrait
{
    private ResponseInterface $response;

    public function getOriginalResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function setOriginalResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }
}
