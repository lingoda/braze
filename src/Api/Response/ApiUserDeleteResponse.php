<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Response;

class ApiUserDeleteResponse extends ApiResponse
{
    /**
     * Number of user ids queued for deletion
     */
    private int $deleted;

    public function __construct(string $message, int $deleted, array $errors = [])
    {
        parent::__construct($message, $errors);
        $this->deleted = $deleted;
    }

    public function getDeleted(): int
    {
        return $this->deleted;
    }
}
