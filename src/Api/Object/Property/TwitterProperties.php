<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object\Property;

interface TwitterProperties
{
    public const ID = 'id';
    public const SCREEN_NAME = 'screen_name';
    public const FOLLOWERS_COUNT = 'followers_count';
    public const FRIENDS_COUNT = 'friends_count';
    public const STATUSES_COUNT = 'statuses_count';
}
