<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Response;

class ApiUserSegmentExportResponse extends ApiResponse
{
    private ?string $url;

    private string $objectPrefix;

    public function __construct(string $message, string $object_prefix, ?string $url = null, array $errors = [])
    {
        parent::__construct($message, $errors);
        $this->url = $url;
        $this->objectPrefix = $object_prefix;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getObjectPrefix(): string
    {
        return $this->objectPrefix;
    }
}
