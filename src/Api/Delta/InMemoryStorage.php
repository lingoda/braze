<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Delta;

/**
 * For testing
 */
final class InMemoryStorage implements StorageInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * {@inheritDoc}
     */
    public function read(string $id): ?array
    {
        return $this->data[$id] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function write(string $id, array $options): void
    {
        $this->data[$id] = $options;
    }

    public function clean(): void
    {
        $this->data = [];
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
