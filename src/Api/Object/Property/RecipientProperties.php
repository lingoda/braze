<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object\Property;

interface RecipientProperties
{
    public const USER_ALIAS = 'user_alias';
    public const EXTERNAL_USER_ID = 'external_user_id';
    public const TRIGGER_PROPERTIES = 'trigger_properties';
    public const CANVAS_ENTRY_PROPERTIES = 'canvas_entry_properties';
}
