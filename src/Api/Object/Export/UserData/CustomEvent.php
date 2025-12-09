<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Object\Export\UserData;

use DateTimeImmutable;
use DateTimeInterface;
use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\SerializableExportObject;

final class CustomEvent implements SerializableExportObject
{
    public function __construct(
        public readonly string $name,
        public readonly DateTimeImmutable $first,
        public readonly DateTimeImmutable $last,
        public readonly int $count,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $first = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $data['first']);
        $last = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $data['last']);

        if ($first === false) {
            throw new InvalidArgumentException('Invalid date format for first');
        }
        if ($last === false) {
            throw new InvalidArgumentException('Invalid date format for last');
        }

        return new self(
            name: $data['name'],
            first: $first,
            last: $last,
            count: $data['count'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'first' => $this->first->format(DateTimeInterface::RFC3339_EXTENDED),
            'last' => $this->last->format(DateTimeInterface::RFC3339_EXTENDED),
            'count' => $this->count,
        ];
    }
}
