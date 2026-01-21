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
    public const FIELD_CREATED_AT = 'created_at';
    public const FIELD_EXTERNAL_ID = 'external_id';
    public const FIELD_BRAZE_ID = 'braze_id';
    public const FIELD_USER_ALIASES = 'user_aliases';
    public const FIELD_RANDOM_BUCKET = 'random_bucket';
    public const FIELD_FIRST_NAME = 'first_name';
    public const FIELD_LAST_NAME = 'last_name';
    public const FIELD_EMAIL = 'email';
    public const FIELD_DOB = 'dob';
    public const FIELD_HOME_CITY = 'home_city';
    public const FIELD_COUNTRY = 'country';
    public const FIELD_PHONE = 'phone';
    public const FIELD_LANGUAGE = 'language';
    public const FIELD_TIME_ZONE = 'time_zone';
    public const FIELD_GENDER = 'gender';
    public const FIELD_LAST_COORDINATES = 'last_coordinates';
    public const FIELD_PUSH_SUBSCRIBE = 'push_subscribe';
    public const FIELD_PUSH_OPTED_IN_AT = 'push_opted_in_at';
    public const FIELD_EMAIL_SUBSCRIBE = 'email_subscribe';
    public const FIELD_TOTAL_REVENUE = 'total_revenue';
    public const FIELD_ATTRIBUTED_CAMPAIGN = 'attributed_campaign';
    public const FIELD_ATTRIBUTED_SOURCE = 'attributed_source';
    public const FIELD_ATTRIBUTED_ADGROUP = 'attributed_adgroup';
    public const FIELD_ATTRIBUTED_AD = 'attributed_ad';
    public const FIELD_CUSTOM_ATTRIBUTES = 'custom_attributes';
    public const FIELD_CUSTOM_EVENTS = 'custom_events';
    public const FIELD_PURCHASES = 'purchases';
    public const FIELD_DEVICES = 'devices';
    public const FIELD_PUSH_TOKENS = 'push_tokens';
    public const FIELD_APPS = 'apps';
    public const FIELD_CAMPAIGNS_RECEIVED = 'campaigns_received';
    public const FIELD_CANVASES_RECEIVED = 'canvases_received';
    public const FIELD_CARDS_CLICKED = 'cards_clicked';
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
        $createdAt = isset($data[self::FIELD_CREATED_AT])
            ? DateTimeImmutable::createFromFormat(self::DATE_FORMAT_CREATED_AT, $data[self::FIELD_CREATED_AT])
            : null;
        $dob = isset($data[self::FIELD_DOB])
            ? DateTimeImmutable::createFromFormat(self::DATE_FORMAT_DOB, $data[self::FIELD_DOB])
            : null;
        $pushOptedInAt = isset($data[self::FIELD_PUSH_OPTED_IN_AT])
            ? DateTimeImmutable::createFromFormat(
                \DateTimeInterface::RFC3339_EXTENDED,
                $data[self::FIELD_PUSH_OPTED_IN_AT]
            )
            : null;

        if ($createdAt === false) {
            throw new InvalidArgumentException('Invalid date format for ' . self::FIELD_CREATED_AT);
        }
        if ($dob === false) {
            throw new InvalidArgumentException('Invalid date format for ' . self::FIELD_DOB);
        }
        if ($pushOptedInAt === false) {
            throw new InvalidArgumentException('Invalid date format for ' . self::FIELD_PUSH_OPTED_IN_AT);
        }

        return new self(
            createdAt: $createdAt,
            externalId: $data[self::FIELD_EXTERNAL_ID] ?? null,
            brazeId: $data[self::FIELD_BRAZE_ID] ?? null,
            userAliases: isset($data[self::FIELD_USER_ALIASES]) ? array_map(
                static fn (array $alias) => new UserAlias($alias['alias_name'], $alias['alias_label']),
                $data[self::FIELD_USER_ALIASES]
            ) : null,
            randomBucket: $data[self::FIELD_RANDOM_BUCKET] ?? null,
            firstName: $data[self::FIELD_FIRST_NAME] ?? null,
            lastName: $data[self::FIELD_LAST_NAME] ?? null,
            email: $data[self::FIELD_EMAIL] ?? null,
            dob: $dob,
            homeCity: $data[self::FIELD_HOME_CITY] ?? null,
            country: $data[self::FIELD_COUNTRY] ?? null,
            phone: $data[self::FIELD_PHONE] ?? null,
            language: $data[self::FIELD_LANGUAGE] ?? null,
            timeZone: $data[self::FIELD_TIME_ZONE] ?? null,
            gender: isset($data[self::FIELD_GENDER]) ? Gender::tryFrom($data[self::FIELD_GENDER]) : null,
            lastCoordinates: $data[self::FIELD_LAST_COORDINATES] ?? null,
            pushSubscribe: $data[self::FIELD_PUSH_SUBSCRIBE] ?? null,
            pushOptedInAt: $pushOptedInAt,
            emailSubscribe: $data[self::FIELD_EMAIL_SUBSCRIBE] ?? null,
            totalRevenue: $data[self::FIELD_TOTAL_REVENUE] ?? null,
            attributedCampaign: $data[self::FIELD_ATTRIBUTED_CAMPAIGN] ?? null,
            attributedSource: $data[self::FIELD_ATTRIBUTED_SOURCE] ?? null,
            attributedAdgroup: $data[self::FIELD_ATTRIBUTED_ADGROUP] ?? null,
            attributedAd: $data[self::FIELD_ATTRIBUTED_AD] ?? null,
            customAttributes: $data[self::FIELD_CUSTOM_ATTRIBUTES] ?? null,
            customEvents: isset($data[self::FIELD_CUSTOM_EVENTS])
                ? array_map(CustomEvent::fromArray(...), $data[self::FIELD_CUSTOM_EVENTS])
                : null,
            purchases: isset($data[self::FIELD_PURCHASES])
                ? array_map(Purchase::fromArray(...), $data[self::FIELD_PURCHASES])
                : null,
            devices: isset($data[self::FIELD_DEVICES])
                ? array_map(Device::fromArray(...), $data[self::FIELD_DEVICES])
                : null,
            pushTokens: isset($data[self::FIELD_PUSH_TOKENS])
                ? array_map(PushToken::fromArray(...), $data[self::FIELD_PUSH_TOKENS])
                : null,
            apps: isset($data[self::FIELD_APPS])
                ? array_map(App::fromArray(...), $data[self::FIELD_APPS])
                : null,
            campaignsReceived: isset($data[self::FIELD_CAMPAIGNS_RECEIVED])
                ? array_map(Campaign::fromArray(...), $data[self::FIELD_CAMPAIGNS_RECEIVED])
                : null,
            canvasesReceived: isset($data[self::FIELD_CANVASES_RECEIVED])
                ? array_map(Canvas::fromArray(...), $data[self::FIELD_CANVASES_RECEIVED])
                : null,
            cardsClicked: isset($data[self::FIELD_CARDS_CLICKED])
                ? array_map(Card::fromArray(...), $data[self::FIELD_CARDS_CLICKED])
                : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            self::FIELD_CREATED_AT => $this->createdAt?->format(self::DATE_FORMAT_CREATED_AT),
            self::FIELD_EXTERNAL_ID => $this->externalId,
            self::FIELD_BRAZE_ID => $this->brazeId,
            self::FIELD_USER_ALIASES => $this->userAliases !== null
                ? array_map(static fn (UserAlias $alias) => $alias->getValue(), $this->userAliases)
                : null,
            self::FIELD_RANDOM_BUCKET => $this->randomBucket,
            self::FIELD_FIRST_NAME => $this->firstName,
            self::FIELD_LAST_NAME => $this->lastName,
            self::FIELD_EMAIL => $this->email,
            self::FIELD_DOB => $this->dob?->format(self::DATE_FORMAT_DOB),
            self::FIELD_HOME_CITY => $this->homeCity,
            self::FIELD_COUNTRY => $this->country,
            self::FIELD_PHONE => $this->phone,
            self::FIELD_LANGUAGE => $this->language,
            self::FIELD_TIME_ZONE => $this->timeZone,
            self::FIELD_GENDER => $this->gender?->value,
            self::FIELD_LAST_COORDINATES => $this->lastCoordinates,
            self::FIELD_PUSH_SUBSCRIBE => $this->pushSubscribe,
            self::FIELD_PUSH_OPTED_IN_AT => $this->pushOptedInAt?->format(\DateTimeInterface::RFC3339_EXTENDED),
            self::FIELD_EMAIL_SUBSCRIBE => $this->emailSubscribe,
            self::FIELD_TOTAL_REVENUE => $this->totalRevenue,
            self::FIELD_ATTRIBUTED_CAMPAIGN => $this->attributedCampaign,
            self::FIELD_ATTRIBUTED_SOURCE => $this->attributedSource,
            self::FIELD_ATTRIBUTED_ADGROUP => $this->attributedAdgroup,
            self::FIELD_ATTRIBUTED_AD => $this->attributedAd,
            self::FIELD_CUSTOM_ATTRIBUTES => $this->customAttributes,
            self::FIELD_CUSTOM_EVENTS => $this->customEvents !== null
                ? array_map(static fn (CustomEvent $e) => $e->toArray(), $this->customEvents)
                : null,
            self::FIELD_PURCHASES => $this->purchases !== null
                ? array_map(static fn (Purchase $p) => $p->toArray(), $this->purchases)
                : null,
            self::FIELD_DEVICES => $this->devices !== null
                ? array_map(static fn (Device $d) => $d->toArray(), $this->devices)
                : null,
            self::FIELD_PUSH_TOKENS => $this->pushTokens !== null
                ? array_map(static fn (PushToken $t) => $t->toArray(), $this->pushTokens)
                : null,
            self::FIELD_APPS => $this->apps !== null
                ? array_map(static fn (App $a) => $a->toArray(), $this->apps)
                : null,
            self::FIELD_CAMPAIGNS_RECEIVED => $this->campaignsReceived !== null
                ? array_map(static fn (Campaign $c) => $c->toArray(), $this->campaignsReceived)
                : null,
            self::FIELD_CANVASES_RECEIVED => $this->canvasesReceived !== null
                ? array_map(static fn (Canvas $c) => $c->toArray(), $this->canvasesReceived)
                : null,
            self::FIELD_CARDS_CLICKED => $this->cardsClicked !== null
                ? array_map(static fn (Card $c) => $c->toArray(), $this->cardsClicked)
                : null,
        ], static fn ($value) => $value !== null);
    }
}
