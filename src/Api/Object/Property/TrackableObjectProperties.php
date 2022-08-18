<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object\Property;

/**
 * Common properties between Trackable Objects
 */
interface TrackableObjectProperties
{
    public const EXTERNAL_ID = 'external_id';
    public const BRAZE_ID = 'braze_id';
    public const USER_ALIAS = 'user_alias';
    public const UPDATE_EXISTING_ONLY = '_update_existing_only';
}
