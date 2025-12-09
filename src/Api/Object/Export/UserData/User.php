<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Object\Export\UserData;

use DateTimeImmutable;
use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\SerializableExportObject;
use Lingoda\BrazeBundle\Api\Object\UserAlias;

/**
 * Exported user data from Braze API.
 *
 * @see https://www.braze.com/docs/api/endpoints/export/user_data/post_users_identifier
 * @see https://www.braze.com/docs/api/endpoints/export/user_data/post_users_segment
 * @see https://www.braze.com/docs/api/endpoints/export/user_data/post_users_global_control_group
 */
final class User implements SerializableExportObject
{
    private const DATE_FORMAT_CREATED_AT = 'Y-m-d H:i:s.v e';
    private const DATE_FORMAT_DOB = 'Y-m-d';

    public function __construct(
        public readonly ?DateTimeImmutable $createdAt = null,
        public readonly ?string $externalId = null,
        public readonly ?string $brazeId = null,
        /** @var UserAlias[]|null */
        public readonly ?array $userAliases = null,
        public readonly ?int $randomBucket = null,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $email = null,
        public readonly ?DateTimeImmutable $dob = null,
        public readonly ?string $homeCity = null,
        public readonly ?string $country = null,
        public readonly ?string $phone = null,
        public readonly ?string $language = null,
        public readonly ?string $timeZone = null,
        public readonly ?Gender $gender = null,
        /** @var float[]|null [longitude, latitude] */
        public readonly ?array $lastCoordinates = null,
        public readonly ?string $pushSubscribe = null,
        public readonly ?DateTimeImmutable $pushOptedInAt = null,
        public readonly ?string $emailSubscribe = null,
        public readonly ?float $totalRevenue = null,
        public readonly ?string $attributedCampaign = null,
        public readonly ?string $attributedSource = null,
        public readonly ?string $attributedAdgroup = null,
        public readonly ?string $attributedAd = null,
        /** @var array<string, mixed>|null */
        public readonly ?array $customAttributes = null,
        /** @var CustomEvent[]|null */
        public readonly ?array $customEvents = null,
        /** @var Purchase[]|null */
        public readonly ?array $purchases = null,
        /** @var Device[]|null */
        public readonly ?array $devices = null,
        /** @var PushToken[]|null */
        public readonly ?array $pushTokens = null,
        /** @var App[]|null */
        public readonly ?array $apps = null,
        /** @var Campaign[]|null */
        public readonly ?array $campaignsReceived = null,
        /** @var Canvas[]|null */
        public readonly ?array $canvasesReceived = null,
        /** @var Card[]|null */
        public readonly ?array $cardsClicked = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $createdAt = isset($data['created_at'])
            ? DateTimeImmutable::createFromFormat(self::DATE_FORMAT_CREATED_AT, $data['created_at'])
            : null;
        $dob = isset($data['dob'])
            ? DateTimeImmutable::createFromFormat(self::DATE_FORMAT_DOB, $data['dob'])
            : null;
        $pushOptedInAt = isset($data['push_opted_in_at'])
            ? DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $data['push_opted_in_at'])
            : null;

        if ($createdAt === false) {
            throw new InvalidArgumentException('Invalid date format for created_at');
        }
        if ($dob === false) {
            throw new InvalidArgumentException('Invalid date format for dob');
        }
        if ($pushOptedInAt === false) {
            throw new InvalidArgumentException('Invalid date format for push_opted_in_at');
        }

        return new self(
            createdAt: $createdAt,
            externalId: $data['external_id'] ?? null,
            brazeId: $data['braze_id'] ?? null,
            userAliases: isset($data['user_aliases']) ? array_map(
                static fn (array $alias) => new UserAlias($alias['alias_name'], $alias['alias_label']),
                $data['user_aliases']
            ) : null,
            randomBucket: $data['random_bucket'] ?? null,
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            email: $data['email'] ?? null,
            dob: $dob,
            homeCity: $data['home_city'] ?? null,
            country: $data['country'] ?? null,
            phone: $data['phone'] ?? null,
            language: $data['language'] ?? null,
            timeZone: $data['time_zone'] ?? null,
            gender: isset($data['gender']) ? Gender::tryFrom($data['gender']) : null,
            lastCoordinates: $data['last_coordinates'] ?? null,
            pushSubscribe: $data['push_subscribe'] ?? null,
            pushOptedInAt: $pushOptedInAt,
            emailSubscribe: $data['email_subscribe'] ?? null,
            totalRevenue: $data['total_revenue'] ?? null,
            attributedCampaign: $data['attributed_campaign'] ?? null,
            attributedSource: $data['attributed_source'] ?? null,
            attributedAdgroup: $data['attributed_adgroup'] ?? null,
            attributedAd: $data['attributed_ad'] ?? null,
            customAttributes: $data['custom_attributes'] ?? null,
            customEvents: isset($data['custom_events'])
                ? array_map(CustomEvent::fromArray(...), $data['custom_events'])
                : null,
            purchases: isset($data['purchases'])
                ? array_map(Purchase::fromArray(...), $data['purchases'])
                : null,
            devices: isset($data['devices'])
                ? array_map(Device::fromArray(...), $data['devices'])
                : null,
            pushTokens: isset($data['push_tokens'])
                ? array_map(PushToken::fromArray(...), $data['push_tokens'])
                : null,
            apps: isset($data['apps'])
                ? array_map(App::fromArray(...), $data['apps'])
                : null,
            campaignsReceived: isset($data['campaigns_received'])
                ? array_map(Campaign::fromArray(...), $data['campaigns_received'])
                : null,
            canvasesReceived: isset($data['canvases_received'])
                ? array_map(Canvas::fromArray(...), $data['canvases_received'])
                : null,
            cardsClicked: isset($data['cards_clicked'])
                ? array_map(Card::fromArray(...), $data['cards_clicked'])
                : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'created_at' => $this->createdAt?->format(self::DATE_FORMAT_CREATED_AT),
            'external_id' => $this->externalId,
            'braze_id' => $this->brazeId,
            'user_aliases' => $this->userAliases !== null
                ? array_map(static fn (UserAlias $alias) => $alias->getValue(), $this->userAliases)
                : null,
            'random_bucket' => $this->randomBucket,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'dob' => $this->dob?->format(self::DATE_FORMAT_DOB),
            'home_city' => $this->homeCity,
            'country' => $this->country,
            'phone' => $this->phone,
            'language' => $this->language,
            'time_zone' => $this->timeZone,
            'gender' => $this->gender?->value,
            'last_coordinates' => $this->lastCoordinates,
            'push_subscribe' => $this->pushSubscribe,
            'push_opted_in_at' => $this->pushOptedInAt?->format(\DateTimeInterface::RFC3339_EXTENDED),
            'email_subscribe' => $this->emailSubscribe,
            'total_revenue' => $this->totalRevenue,
            'attributed_campaign' => $this->attributedCampaign,
            'attributed_source' => $this->attributedSource,
            'attributed_adgroup' => $this->attributedAdgroup,
            'attributed_ad' => $this->attributedAd,
            'custom_attributes' => $this->customAttributes,
            'custom_events' => $this->customEvents !== null
                ? array_map(static fn (CustomEvent $e) => $e->toArray(), $this->customEvents)
                : null,
            'purchases' => $this->purchases !== null
                ? array_map(static fn (Purchase $p) => $p->toArray(), $this->purchases)
                : null,
            'devices' => $this->devices !== null
                ? array_map(static fn (Device $d) => $d->toArray(), $this->devices)
                : null,
            'push_tokens' => $this->pushTokens !== null
                ? array_map(static fn (PushToken $t) => $t->toArray(), $this->pushTokens)
                : null,
            'apps' => $this->apps !== null
                ? array_map(static fn (App $a) => $a->toArray(), $this->apps)
                : null,
            'campaigns_received' => $this->campaignsReceived !== null
                ? array_map(static fn (Campaign $c) => $c->toArray(), $this->campaignsReceived)
                : null,
            'canvases_received' => $this->canvasesReceived !== null
                ? array_map(static fn (Canvas $c) => $c->toArray(), $this->canvasesReceived)
                : null,
            'cards_clicked' => $this->cardsClicked !== null
                ? array_map(static fn (Card $c) => $c->toArray(), $this->cardsClicked)
                : null,
        ], static fn ($value) => $value !== null);
    }
}
