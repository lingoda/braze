<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Response;

use Lingoda\BrazeBundle\Api\Object\Export\UserData\User;

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
     * @return User[]
     */
    public function getUsers(): array
    {
        return array_map(User::fromArray(...), $this->users);
    }

    /**
     * @return mixed[]
     */
    public function getUsersRaw(): array
    {
        return $this->users;
    }
}
