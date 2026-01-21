<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Object\Export\SerializableExportObject;

final class PushToken implements SerializableExportObject
{
    public function __construct(
        public readonly string $app,
        public readonly string $platform,
        public readonly string $token,
        public readonly string $deviceId,
        public readonly bool $notificationsEnabled,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            app: $data['app'],
            platform: $data['platform'],
            token: $data['token'],
            deviceId: $data['device_id'],
            notificationsEnabled: $data['notifications_enabled'],
        );
    }

    public function toArray(): array
    {
        return [
            'app' => $this->app,
            'platform' => $this->platform,
            'token' => $this->token,
            'device_id' => $this->deviceId,
            'notifications_enabled' => $this->notificationsEnabled,
        ];
    }
}
