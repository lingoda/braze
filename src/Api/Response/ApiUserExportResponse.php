<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Response;

class ApiUserExportResponse extends ApiResponse
{
    /**
     * @var mixed[]
     */
    private array $users;

    /**
     * @param mixed[] $users
     */
    public function __construct(string $message, array $users, array $errors = [])
    {
        parent::__construct($message, $errors);
        $this->users = $users;
    }

    /**
     * @return mixed[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }
}
