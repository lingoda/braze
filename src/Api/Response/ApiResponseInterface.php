<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Response;

interface ApiResponseInterface
{
    public const MESSAGE_SUCCESS = 'success';
    public const MESSAGE_QUEUED = 'queued';

    public function getMessage(): string;

    /**
     * @return string[]
     */
    public function getErrors(): array;

    public function isSuccess(): bool;

    public function isQueued(): bool;

    public function isFatal(): bool;

    public function hasErrors(): bool;
}
