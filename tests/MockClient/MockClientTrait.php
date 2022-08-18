<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\MockClient;

use Lingoda\BrazeBundle\Test\MockClientCallback;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;

trait MockClientTrait
{
    /**
     * @param array<string, MockResponse> $responses
     */
    protected function setMockClientResponses(array $responses): void
    {
        /** @var MockClientCallback $mockClientCallback */
        $mockClientCallback = self::$container->get(MockClientCallback::class);
        $mockClientCallback->setResponses($responses);
    }

    /**
     * @param array<string, mixed> $responeBody
     */
    protected function createJsonMockResponse(array $responeBody, int $statusCode = Response::HTTP_OK): MockResponse
    {
        return new MockResponse((string) json_encode($responeBody), ['http_code' => $statusCode]);
    }
}
