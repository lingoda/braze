<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Object\Export\SerializableExportObject;

final class Device implements SerializableExportObject
{
    public function __construct(
        public readonly string $model,
        public readonly string $os,
        public readonly string $deviceId,
        public readonly bool $adTrackingEnabled,
        public readonly ?string $carrier = null,
        public readonly ?string $idfv = null,
        public readonly ?string $idfa = null,
        public readonly ?string $googleAdId = null,
        public readonly ?string $rokuAdId = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            model: $data['model'],
            os: $data['os'],
            deviceId: $data['device_id'],
            adTrackingEnabled: $data['ad_tracking_enabled'],
            carrier: $data['carrier'] ?? null,
            idfv: $data['idfv'] ?? null,
            idfa: $data['idfa'] ?? null,
            googleAdId: $data['google_ad_id'] ?? null,
            rokuAdId: $data['roku_ad_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'model' => $this->model,
            'os' => $this->os,
            'device_id' => $this->deviceId,
            'ad_tracking_enabled' => $this->adTrackingEnabled,
            'carrier' => $this->carrier,
            'idfv' => $this->idfv,
            'idfa' => $this->idfa,
            'google_ad_id' => $this->googleAdId,
            'roku_ad_id' => $this->rokuAdId,
        ], static fn ($value) => $value !== null);
    }
}
