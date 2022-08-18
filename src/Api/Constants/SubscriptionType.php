<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Constants;

final class SubscriptionType
{
    /**
     * explicitly registered to receive email messages
     */
    public const OPTED_IN = 'opted_in';

    /**
     * explicitly opted out of email messages
     */
    public const UNSUBSCRIBED = 'unsubscribed';

    /**
     * neither opted in nor out
     */
    public const SUBSCRIBED = 'subscribed';

    /**
     * @var array<int, string|null>
     */
    public static array $choices = [
        self::OPTED_IN,
        self::SUBSCRIBED,
        self::UNSUBSCRIBED,
        null,
    ];

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
