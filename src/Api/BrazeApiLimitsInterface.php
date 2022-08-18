<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api;

interface BrazeApiLimitsInterface
{
    public const BRAZE_ALIAS_API_LIMIT = 50;
    public const BRAZE_TRACK_API_LIMIT = 75;
    public const BRAZE_IDENTIFY_API_LIMIT = 50;
    public const BRAZE_USER_EXPORT_LIMIT = 50;
}
