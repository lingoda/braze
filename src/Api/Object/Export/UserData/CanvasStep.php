<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Object\Export\UserData;

use DateTimeImmutable;
use DateTimeInterface;
use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\SerializableExportObject;

final class CanvasStep implements SerializableExportObject
{
    public function __construct(
        public readonly string $name,
        public readonly string $apiCanvasStepId,
        public readonly DateTimeImmutable $lastReceived,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $lastReceived = DateTimeImmutable::createFromFormat(
            DateTimeInterface::RFC3339_EXTENDED,
            $data['last_received']
        );

        if ($lastReceived === false) {
            throw new InvalidArgumentException('Invalid date format for last_received');
        }

        return new self(
            name: $data['name'],
            apiCanvasStepId: $data['api_canvas_step_id'],
            lastReceived: $lastReceived,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'api_canvas_step_id' => $this->apiCanvasStepId,
            'last_received' => $this->lastReceived->format(DateTimeInterface::RFC3339_EXTENDED),
        ];
    }
}
