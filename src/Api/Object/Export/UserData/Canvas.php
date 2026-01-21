<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Object\Export\UserData;

use DateTimeImmutable;
use DateTimeInterface;
use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\SerializableExportObject;

final class Canvas implements SerializableExportObject
{
    public function __construct(
        public readonly string $name,
        public readonly string $apiCanvasId,
        public readonly DateTimeImmutable $lastReceivedMessage,
        public readonly DateTimeImmutable $lastEntered,
        public readonly DateTimeImmutable $lastExited,
        public readonly bool $inControl,
        public readonly ?string $variationName = null,
        /** @var CanvasStep[]|null */
        public readonly ?array $stepsReceived = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $lastReceivedMessage = DateTimeImmutable::createFromFormat(
            DateTimeInterface::RFC3339_EXTENDED,
            $data['last_received_message']
        );
        $lastEntered = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $data['last_entered']);
        $lastExited = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $data['last_exited']);

        if ($lastReceivedMessage === false) {
            throw new InvalidArgumentException('Invalid date format for last_received_message');
        }
        if ($lastEntered === false) {
            throw new InvalidArgumentException('Invalid date format for last_entered');
        }
        if ($lastExited === false) {
            throw new InvalidArgumentException('Invalid date format for last_exited');
        }

        return new self(
            name: $data['name'],
            apiCanvasId: $data['api_canvas_id'],
            lastReceivedMessage: $lastReceivedMessage,
            lastEntered: $lastEntered,
            lastExited: $lastExited,
            inControl: $data['in_control'],
            variationName: $data['variation_name'] ?? null,
            stepsReceived: isset($data['steps_received'])
                ? array_map(CanvasStep::fromArray(...), $data['steps_received'])
                : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'api_canvas_id' => $this->apiCanvasId,
            'last_received_message' => $this->lastReceivedMessage->format(DateTimeInterface::RFC3339_EXTENDED),
            'last_entered' => $this->lastEntered->format(DateTimeInterface::RFC3339_EXTENDED),
            'last_exited' => $this->lastExited->format(DateTimeInterface::RFC3339_EXTENDED),
            'in_control' => $this->inControl,
            'variation_name' => $this->variationName,
            'steps_received' => $this->stepsReceived !== null
                ? array_map(static fn (CanvasStep $step) => $step->toArray(), $this->stepsReceived)
                : null,
        ], static fn ($value) => $value !== null);
    }
}
