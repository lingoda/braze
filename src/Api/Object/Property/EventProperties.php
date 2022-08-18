<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object\Property;

interface EventProperties extends TrackableObjectProperties
{
    public const APP_ID = 'app_id';
    public const TIME = 'time';
    public const NAME = 'name';
    public const PROPERTIES = 'properties';
}
