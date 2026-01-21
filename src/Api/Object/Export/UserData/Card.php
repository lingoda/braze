<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Object\Export\SerializableExportObject;

final class Card implements SerializableExportObject
{
    public function __construct(
        public readonly string $name,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
