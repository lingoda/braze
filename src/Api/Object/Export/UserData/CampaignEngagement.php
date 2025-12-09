<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Object\Export\SerializableExportObject;

final class CampaignEngagement implements SerializableExportObject
{
    public function __construct(
        public readonly ?bool $openedEmail = null,
        public readonly ?bool $openedPush = null,
        public readonly ?bool $clickedEmail = null,
        public readonly ?bool $clickedTriggeredInAppMessage = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            openedEmail: $data['opened_email'] ?? null,
            openedPush: $data['opened_push'] ?? null,
            clickedEmail: $data['clicked_email'] ?? null,
            clickedTriggeredInAppMessage: $data['clicked_triggered_in_app_message'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'opened_email' => $this->openedEmail,
            'opened_push' => $this->openedPush,
            'clicked_email' => $this->clickedEmail,
            'clicked_triggered_in_app_message' => $this->clickedTriggeredInAppMessage,
        ], static fn ($value) => $value !== null);
    }
}
