<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Object\Export\UserData;

use DateTimeImmutable;
use DateTimeInterface;
use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\SerializableExportObject;

final class Campaign implements SerializableExportObject
{
    public function __construct(
        public readonly string $name,
        public readonly string $apiCampaignId,
        public readonly DateTimeImmutable $lastReceived,
        public readonly CampaignEngagement $engaged,
        public readonly bool $converted,
        public readonly bool $inControl,
        public readonly ?string $variationName = null,
        public readonly ?string $variationApiId = null,
        public readonly ?bool $multipleConverted = null,
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
            apiCampaignId: $data['api_campaign_id'],
            lastReceived: $lastReceived,
            engaged: CampaignEngagement::fromArray($data['engaged']),
            converted: $data['converted'],
            inControl: $data['in_control'],
            variationName: $data['variation_name'] ?? null,
            variationApiId: $data['variation_api_id'] ?? null,
            multipleConverted: $data['multiple_converted'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'api_campaign_id' => $this->apiCampaignId,
            'last_received' => $this->lastReceived->format(DateTimeInterface::RFC3339_EXTENDED),
            'engaged' => $this->engaged->toArray(),
            'converted' => $this->converted,
            'in_control' => $this->inControl,
            'variation_name' => $this->variationName,
            'variation_api_id' => $this->variationApiId,
            'multiple_converted' => $this->multipleConverted,
        ], static fn ($value) => $value !== null);
    }
}
