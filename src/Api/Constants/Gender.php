<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Constants;

final class Gender
{
    public const MALE = 'M';
    public const FEMALE = 'F';
    public const OTHER = 'O';
    public const NOT_APPLICABLE = 'N';
    public const PREFER_NOT_TO_SAY = 'P';
    public const UNKNOWN = 'nil';

    /**
     * @var array<int, string|null>
     */
    public static array $choices = [
        self::FEMALE,
        self::MALE,
        self::NOT_APPLICABLE,
        self::OTHER,
        self::PREFER_NOT_TO_SAY,
        self::UNKNOWN,
        null,
    ];

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
