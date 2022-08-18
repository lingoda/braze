<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Test;

use Symfony\Contracts\HttpClient\ResponseInterface;
use Webmozart\Assert\Assert;

final class MockClientCallback
{
    /**
     * @var array<string, ResponseInterface|ResponseInterface[]>
     */
    private array $responses;
    private string $baseUri;

    /**
     * @param array<string, ResponseInterface|ResponseInterface[]> $responses
     */
    public function __construct(string $baseUri, array $responses = [])
    {
        $this->baseUri = $baseUri;
        $this->setResponses($responses);
    }

    /**
     * @param array<string, ResponseInterface|ResponseInterface[]> $responses
     */
    public function setResponses(array $responses): void
    {
        $this->responses = $responses;
    }

    /**
     * @param array<string, mixed>  $options
     */
    public function __invoke(string $method, string $url, array $options = []): ResponseInterface
    {
        $response = $this->responses[str_replace($this->baseUri, '', $url)] ?? null;
        if (null === $response) {
            throw new \LogicException('There is no response for url ' . $url);
        }

        if (\is_array($response)) {
            $response = array_shift($response);
            Assert::isInstanceOf($response, ResponseInterface::class);

            return $response;
        }

        return $response;
    }
}
