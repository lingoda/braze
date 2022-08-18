<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Delta;

interface StorageInterface
{
    /**
     * @return array<string, mixed>|null
     */
    public function read(string $id): ?array;

    /**
     * @param array<string, mixed> $options
     */
    public function write(string $id, array $options): void;
}
