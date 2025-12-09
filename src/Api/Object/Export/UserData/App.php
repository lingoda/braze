<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Object\Export\UserData;

use DateTimeImmutable;
use DateTimeInterface;
use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\SerializableExportObject;

final class App implements SerializableExportObject
{
    public function __construct(
        public readonly string $name,
        public readonly string $platform,
        public readonly string $version,
        public readonly int $sessions,
        public readonly DateTimeImmutable $firstUsed,
        public readonly DateTimeImmutable $lastUsed,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $firstUsed = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $data['first_used']);
        $lastUsed = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $data['last_used']);

        if ($firstUsed === false) {
            throw new InvalidArgumentException('Invalid date format for first_used');
        }
        if ($lastUsed === false) {
            throw new InvalidArgumentException('Invalid date format for last_used');
        }

        return new self(
            name: $data['name'],
            platform: $data['platform'],
            version: $data['version'],
            sessions: $data['sessions'],
            firstUsed: $firstUsed,
            lastUsed: $lastUsed,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'platform' => $this->platform,
            'version' => $this->version,
            'sessions' => $this->sessions,
            'first_used' => $this->firstUsed->format(DateTimeInterface::RFC3339_EXTENDED),
            'last_used' => $this->lastUsed->format(DateTimeInterface::RFC3339_EXTENDED),
        ];
    }
}
