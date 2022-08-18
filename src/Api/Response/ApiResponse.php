<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Response;

class ApiResponse implements ApiResponseInterface
{
    protected string $message;

    /**
     * @var string[]
     */
    protected array $errors;

    /**
     * @param string[] $errors
     */
    public function __construct(string $message, array $errors = [])
    {
        $this->message = $message;
        $this->errors = $errors;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isSuccess(): bool
    {
        return $this->message === self::MESSAGE_SUCCESS || $this->isQueued();
    }

    public function isQueued(): bool
    {
        return $this->message === self::MESSAGE_QUEUED;
    }

    public function isFatal(): bool
    {
        return !$this->isSuccess() && !$this->isQueued();
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
