<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Exception;

use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Throwable;

/**
 * Wrapping the http exception
 */
class HttpException extends \Exception implements BrazeApiException
{
    final protected function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(ExceptionInterface $e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }
}
